<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\BarangUnit;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PeminjamanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Peminjaman::with(['user', 'barang', 'barangUnit'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $peminjaman = $query->paginate(10);
        return view('staff.peminjaman.index', compact('peminjaman'));
    }

    public function show($id)
    {
        $peminjaman = Peminjaman::with(['user', 'barang', 'barangUnit', 'pengembalian'])->findOrFail($id);
        return view('staff.peminjaman.show', compact('peminjaman'));
    }

    /**
     * Approve the specified loan request.
     */
    public function approve(Request $request, $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        if ($peminjaman->status !== 'pending') {
            return back()->with('error', 'Hanya peminjaman pending yang bisa disetujui.');
        }

        $request->validate([
            'barang_unit_ids' => 'required|array',
            'barang_unit_ids.*' => 'required|exists:barang_unit,id|distinct',
        ]);

        $unitIds = $request->barang_unit_ids;
        $approvedCount = count($unitIds);

        if ($approvedCount > $peminjaman->jumlah) {
            return back()->with('error', 'Jumlah unit yang dipilih melebihi permintaan.');
        }

        $rejectedCount = $peminjaman->jumlah - $approvedCount;

        // Verify units availability
        $units = BarangUnit::whereIn('id', $unitIds)->get();
        foreach ($units as $unit) {
            if ($unit->status !== 'aktif') {
                return back()->with('error', 'Unit ' . $unit->kode_unit . ' tidak tersedia (Rusak/Maintenance).');
            }

            // Check if borrowed
            $isBorrowed = Peminjaman::where('barang_unit_id', $unit->id)
                ->whereIn('status', ['active', 'approved'])
                ->exists();

            if ($isBorrowed) {
                return back()->with('error', 'Unit ' . $unit->kode_unit . ' sedang dipinjam.');
            }
        }

        // Logic Approval
        if ($approvedCount > 0) {
            // 1. Assign First Unit to the original record
            $firstUnitId = array_shift($unitIds);

            $peminjaman->update([
                'status' => 'approved',
                'barang_unit_id' => $firstUnitId,
                'jumlah' => 1, // Reset quantity to 1 per record
            ]);
            $peminjaman->barang->decrement('jumlah_stok');

            // 2. Create new records for remaining units
            foreach ($unitIds as $unitId) {
                $newPeminjaman = $peminjaman->replicate(['items']); // Copy attributes
                $newPeminjaman->kode = Peminjaman::generateKode();
                $newPeminjaman->barang_unit_id = $unitId;
                $newPeminjaman->jumlah = 1;
                $newPeminjaman->status = 'approved';
                $newPeminjaman->created_at = $peminjaman->created_at; // Maintain original request time
                $newPeminjaman->save();

                // Send Email Notification
                if ($peminjaman->user && $peminjaman->user->email) {
                    try {
                        $peminjaman->user->notify(new \App\Notifications\PeminjamanApprovedNotification($peminjaman));
                    } catch (\Exception $e) {
                        \Illuminate\Support\Facades\Log::error('Failed to send approval email', ['error' => $e->getMessage()]);
                    }
                }

                $newPeminjaman->barang->decrement('jumlah_stok');
            }

            // 3. Handle REJECTED (Unselected) units
            if ($rejectedCount > 0) {
                $rejectedPeminjaman = $peminjaman->replicate(['items']);
                $rejectedPeminjaman->kode = Peminjaman::generateKode();
                $rejectedPeminjaman->barang_unit_id = null;
                $rejectedPeminjaman->jumlah = $rejectedCount;
                $rejectedPeminjaman->status = 'rejected';
                $rejectedPeminjaman->keterangan_penolakan = 'Sebagian permintaan ditolak karena unit tidak dipilih oleh staff.';
                $rejectedPeminjaman->created_at = $peminjaman->created_at;
                $rejectedPeminjaman->save();
            }

            \App\Helpers\ActivityLogger::log('Approve Peminjaman', 'Menyetujui peminjaman (Staff): ' . $peminjaman->kode . ' (' . count($request->barang_unit_ids) . ' unit)', $peminjaman);

        } else {
            return back()->with('error', 'Pilih setidaknya satu unit untuk disetujui.');
        }

        return redirect()->route('staff.peminjaman.show', $peminjaman->id)->with('success', 'Peminjaman disetujui. ' . $approvedCount . ' unit telah dialokasikan.');
    }

    /**
     * Reject the specified loan request.
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'keterangan_penolakan' => 'required|string',
        ]);

        $peminjaman = Peminjaman::findOrFail($id);

        if ($peminjaman->status !== 'pending') {
            return back()->with('error', 'Hanya peminjaman pending yang bisa ditolak.');
        }

        $peminjaman->update([
            'status' => 'rejected',
            'keterangan_penolakan' => $request->keterangan_penolakan,
        ]);

        return back()->with('success', 'Peminjaman ditolak.');
    }

    /**
     * Mark as Active (Taken by user)
     */
    public function activate($id)
    {
        // Block activation (taking item) on weekends
        if (now()->isWeekend()) {
            return back()->with('error', 'Pelayanan pengambilan barang tutup pada hari Sabtu dan Minggu.');
        }

        $peminjaman = Peminjaman::findOrFail($id);
        if ($peminjaman->status !== 'approved') {
            return back()->with('error', 'Peminjaman belum disetujui.');
        }

        $peminjaman->update(['status' => 'active']);

        return back()->with('success', 'Status peminjaman diubah menjadi Aktif (Barang Diambil).');
    }

    /**
     * Generate Proof of Loan (PDF/Print)
     */
    public function cetakBukti($id)
    {
        $peminjaman = Peminjaman::with(['user', 'barang', 'barangUnit'])->findOrFail($id);

        if (!in_array($peminjaman->status, ['approved', 'active', 'completed'])) {
            return back()->with('error', 'Bukti peminjaman hanya untuk status Disetujui/Aktif.');
        }

        // Generate QR Content (URL to this PDF)
        $url = route('staff.peminjaman.bukti', $id);

        // Render QR as SVG Base64 (Avoids ImageMagick dependency)
        $qrRaw = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(150)->generate($url);
        $qrCodeImage = 'data:image/svg+xml;base64,' . base64_encode($qrRaw);

        $pdf = Pdf::loadView('staff.peminjaman.bukti', compact('peminjaman', 'qrCodeImage'));
        $pdf->setPaper('A4', 'portrait');

        // Inject Auto-Print JavaScript
        $pdf->render();
        // Auto-print removed upon request

        return $pdf->stream('Bukti-Peminjaman-' . $peminjaman->kode . '.pdf');
    }

    /**
     * Show Return Form
     */
    public function returnForm($id)
    {
        // Block return form access on weekends
        if (now()->isWeekend()) {
            return back()->with('error', 'Pelayanan pengembalian barang tutup pada hari Sabtu dan Minggu.');
        }

        $peminjaman = Peminjaman::with(['user', 'barang', 'barangUnit'])->findOrFail($id);

        if ($peminjaman->status !== 'active') {
            return back()->with('error', 'Hanya peminjaman aktif yang bisa dikembalikan.');
        }

        return view('staff.peminjaman.return', compact('peminjaman'));
    }

    /**
     * Store Return logic
     */
    public function storeReturn(Request $request, $id)
    {
        // Block processing return on weekends
        if (now()->isWeekend()) {
            return back()->with('error', 'Pelayanan pengembalian barang tutup pada hari Sabtu dan Minggu.');
        }

        $request->validate([
            'tgl_kembali' => 'required|date',
            'kondisi' => 'required|in:baik,rusak_ringan,rusak_berat,hilang',
            'denda' => 'nullable|numeric|min:0',
            'keterangan' => 'nullable|string',
            'foto' => 'nullable|image|max:2048',
        ]);

        // Validate Return Date (No Weekends)
        $tglKembali = \Carbon\Carbon::parse($request->tgl_kembali);
        if ($tglKembali->isWeekend()) {
            return back()->withInput()->with('error', 'Hari Sabtu/Minggu tidak dapat dipilih. Tidak bisa meminjam/mengembalikan barang di hari libur.');
        }

        $peminjaman = Peminjaman::with('barang', 'barangUnit')->findOrFail($id);

        if ($peminjaman->status !== 'active') {
            return back()->with('error', 'Hanya peminjaman aktif yang bisa dikembalikan.');
        }

        // 1. Create Pengembalian Record
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $imageService = new ImageUploadService();
            $fotoPath = $imageService->upload($request->file('foto'), 'pengembalian_' . time());
        }

        \App\Models\Pengembalian::create([
            'peminjaman_id' => $peminjaman->id,
            'tgl_kembali' => $request->tgl_kembali,
            'kondisi' => $request->kondisi,
            'denda' => $request->denda,
            'keterangan' => $request->keterangan,
            'petugas_id' => \Illuminate\Support\Facades\Auth::id(),
            'foto' => $fotoPath,
        ]);

        // 2. Update Peminjaman Status
        $peminjaman->update([
            'status' => 'completed',
            'tgl_kembali_aktual' => $request->tgl_kembali,
        ]);

        // 3. Update BarangUnit Status & Stock
        if ($peminjaman->barangUnit) {
            $unit = $peminjaman->barangUnit;
            $statusUnit = 'aktif';

            if ($request->kondisi == 'rusak_ringan') {
                $statusUnit = 'maintenance';
            } elseif ($request->kondisi == 'rusak_berat' || $request->kondisi == 'hilang') {
                $statusUnit = 'rusak';
            }

            $unit->update(['status' => $statusUnit]);

            // Increment generic stock ONLY if good condition
            if ($request->kondisi == 'baik') {
                $peminjaman->barang->increment('jumlah_stok');
            }
        }

        return redirect()->route('staff.peminjaman.index')->with('success', 'Barang berhasil dikembalikan.');
    }

    /**
     * Redirect by Peminjaman Code (Handle QR Scan text-only)
     */
    public function redirectByKode($kode)
    {
        $peminjaman = Peminjaman::where('kode', $kode)->firstOrFail();

        // If User is Admin or Superadmin, redirect to Admin view
        if (auth()->user()->role == 'admin' || auth()->user()->role == 'superadmin') {
            return redirect()->route('admin.peminjaman.bukti', $peminjaman->id);
        }

        return redirect()->route('staff.peminjaman.bukti', $peminjaman->id);
    }

    /**
     * Show inspection form
     */
    public function inspectForm($id)
    {
        $peminjaman = Peminjaman::with(['barang.kategori', 'barangUnit'])->findOrFail($id);

        // Get checklist template for this category
        $template = \App\Models\ChecklistTemplate::where('kategori_id', $peminjaman->barang->kategori_id)->first();

        // Get existing inspections
        $preInspection = \App\Models\Inspection::where('peminjaman_id', $id)
            ->where('type', 'pre_borrow')
            ->first();
        $postInspection = \App\Models\Inspection::where('peminjaman_id', $id)
            ->where('type', 'post_return')
            ->first();

        // Determine which type of inspection is needed
        $inspectionType = null;
        if ($peminjaman->status === 'approved' && !$preInspection) {
            $inspectionType = 'pre_borrow';
        } elseif ($peminjaman->status === 'active' && $preInspection && !$postInspection) {
            $inspectionType = 'post_return';
        }

        return view('staff.peminjaman.inspect', compact(
            'peminjaman',
            'template',
            'preInspection',
            'postInspection',
            'inspectionType'
        ));
    }

    /**
     * Store inspection data
     */
    public function storeInspection(Request $request, $id)
    {
        $peminjaman = Peminjaman::with('barangUnit')->findOrFail($id);

        $validated = $request->validate([
            'type' => 'required|in:pre_borrow,post_return',
            'checklist_data' => 'nullable|array',
            'photo' => 'nullable|image|max:2048',
            'notes' => 'nullable|string|max:1000',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('inspections', 'public');
        }

        // Create inspection for the unit in the loan
        if ($peminjaman->barangUnit) {
            $unit = $peminjaman->barangUnit;
            $inspection = \App\Models\Inspection::create([
                'peminjaman_id' => $id,
                'barang_unit_id' => $unit->id,
                'type' => $validated['type'],
                'checklist_data' => $validated['checklist_data'] ?? [],
                'photo_path' => $photoPath,
                'notes' => $validated['notes'],
                'inspector_id' => auth()->id(),
                'inspected_at' => now(),
            ]);

            // If post-return, compare with pre-borrow
            if ($validated['type'] === 'post_return') {
                $preInspection = \App\Models\Inspection::where('peminjaman_id', $id)
                    ->where('barang_unit_id', $unit->id)
                    ->where('type', 'pre_borrow')
                    ->first();

                if ($preInspection) {
                    $differences = \App\Models\Inspection::compareInspections($preInspection, $inspection);

                    if (!empty($differences)) {
                        $inspection->update([
                            'has_damage' => true,
                            'damage_details' => json_encode($differences),
                        ]);
                    }
                }
            }
        }

        \App\Helpers\ActivityLogger::log(
            'Inspeksi',
            $validated['type'] === 'pre_borrow'
            ? 'Melakukan inspeksi pra-peminjaman: ' . $peminjaman->kode
            : 'Melakukan inspeksi pasca-pengembalian: ' . $peminjaman->kode,
            $peminjaman
        );

        $message = $validated['type'] === 'pre_borrow'
            ? 'Inspeksi pra-peminjaman berhasil dicatat.'
            : 'Inspeksi pasca-pengembalian berhasil dicatat.';

        return redirect()->route('staff.peminjaman.show', $id)->with('success', $message);
    }
}
