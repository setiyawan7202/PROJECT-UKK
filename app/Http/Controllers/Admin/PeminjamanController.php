<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\BarangUnit;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf; // Ensure barryvdh/laravel-dompdf is installed or handle manual print

class PeminjamanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Peminjaman::query();

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Group by Kode: We select unique codes
        // We use closure to filter search if needed
        // Group by Kode: We select unique codes
        $peminjaman = Peminjaman::select('kode')
            ->distinct()
            ->when($request->status, function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->when($request->search, function ($q) use ($request) {
                $q->where(function ($query) use ($request) {
                    $query->where('kode', 'like', '%' . $request->search . '%')
                        ->orWhereHas('user', function ($u) use ($request) {
                            $u->where('username', 'like', '%' . $request->search . '%') // Assuming username is name
                                ->orWhereHas('siswa', function ($s) use ($request) {
                                    $s->where('nama_lengkap', 'like', '%' . $request->search . '%');
                                })
                                ->orWhereHas('guru', function ($g) use ($request) {
                                    $g->where('nama_lengkap', 'like', '%' . $request->search . '%');
                                });
                        })
                        ->orWhereHas('barang', function ($b) use ($request) {
                            $b->where('nama_barang', 'like', '%' . $request->search . '%');
                        });
                });
            })
            ->orderBy('kode', 'desc')
            ->paginate(10);

        // Pre-fetch first item for each code to get basic info (User, Date, etc.)
        // This avoids N+1 in a smart way or allows simple accessor in view
        // Actually, let's just loop in view and fetch `first()` for metadata, 
        // and `count()` for items. Since it's paginated (10), 10 extra queries is negligible.

        return view('admin.peminjaman.index', compact('peminjaman'));
    }

    public function show(Peminjaman $peminjaman)
    {
        // Load relationships for single peminjaman
        $peminjaman->load(['barang', 'barangUnit', 'user.siswa.kelas', 'barang.kategori', 'pengembalian']);

        // No grouping - just show this single peminjaman
        // Wrap in collection untuk compatibility dengan view
        $peminjamanGroup = collect([$peminjaman]);

        // Fetch template for this item's category
        $templates = [];
        if ($peminjaman->barang && $peminjaman->barang->kategori_id) {
            $template = \App\Models\ChecklistTemplate::where('kategori_id', $peminjaman->barang->kategori_id)->first();
            if ($template) {
                $templates[$peminjaman->barang->kategori_id] = $template;
            }
        }

        return view('admin.peminjaman.show', compact('peminjaman', 'peminjamanGroup', 'templates'));
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

        // Verify availability for SELECTED units only
        $units = BarangUnit::whereIn('id', $unitIds)->get();
        foreach ($units as $unit) {
            // Check 1: Unit must be active (not damaged/maintenance)
            if ($unit->status !== 'aktif') {
                return back()->with('error', 'Unit ' . $unit->kode_unit . ' tidak tersedia (Rusak/Maintenance).');
            }

            // Check 2: Unit must not be currently borrowed (active/approved status)
            $isBorrowed = Peminjaman::where('barang_unit_id', $unit->id)
                ->whereIn('status', ['active', 'approved'])
                ->exists();
            if ($isBorrowed) {
                return back()->with('error', 'Unit ' . $unit->kode_unit . ' sedang dipinjam.');
            }

            // Check 3: Double Booking - Check date overlap for the same unit
            $hasOverlap = Peminjaman::where('barang_unit_id', $unit->id)
                ->whereIn('status', ['approved', 'active'])
                ->where('id', '!=', $peminjaman->id) // Exclude current peminjaman
                ->where(function ($query) use ($peminjaman) {
                    $query->where(function ($q) use ($peminjaman) {
                        // Scenario 1: Existing loan starts during this loan period
                        $q->whereBetween('tgl_pinjam', [$peminjaman->tgl_pinjam, $peminjaman->tgl_kembali_rencana]);
                    })->orWhere(function ($q) use ($peminjaman) {
                        // Scenario 2: Existing loan ends during this loan period
                        $q->whereBetween('tgl_kembali_rencana', [$peminjaman->tgl_pinjam, $peminjaman->tgl_kembali_rencana]);
                    })->orWhere(function ($q) use ($peminjaman) {
                        // Scenario 3: Existing loan completely covers this loan period
                        $q->where('tgl_pinjam', '<=', $peminjaman->tgl_pinjam)
                            ->where('tgl_kembali_rencana', '>=', $peminjaman->tgl_kembali_rencana);
                    });
                })
                ->exists();

            if ($hasOverlap) {
                $overlappingLoan = Peminjaman::where('barang_unit_id', $unit->id)
                    ->whereIn('status', ['approved', 'active'])
                    ->where('id', '!=', $peminjaman->id)
                    ->where(function ($query) use ($peminjaman) {
                        $query->where(function ($q) use ($peminjaman) {
                            $q->whereBetween('tgl_pinjam', [$peminjaman->tgl_pinjam, $peminjaman->tgl_kembali_rencana]);
                        })->orWhere(function ($q) use ($peminjaman) {
                            $q->whereBetween('tgl_kembali_rencana', [$peminjaman->tgl_pinjam, $peminjaman->tgl_kembali_rencana]);
                        })->orWhere(function ($q) use ($peminjaman) {
                            $q->where('tgl_pinjam', '<=', $peminjaman->tgl_pinjam)
                                ->where('tgl_kembali_rencana', '>=', $peminjaman->tgl_kembali_rencana);
                        });
                    })
                    ->first();

                $conflictDate = $overlappingLoan ?
                    \Carbon\Carbon::parse($overlappingLoan->tgl_pinjam)->format('d M Y') . ' - ' .
                    \Carbon\Carbon::parse($overlappingLoan->tgl_kembali_rencana)->format('d M Y') :
                    'periode tidak tersedia';

                return back()->with('error', 'Unit ' . $unit->kode_unit . ' sudah dibooking untuk periode ' . $conflictDate . '. Pilih unit lain atau ubah tanggal peminjaman.');
            }
        }

        // Logic Approval
        if ($approvedCount > 0) {
            // 1. Use the ORIGINAL record for the FIRST approved unit
            $firstUnitId = array_shift($unitIds);

            $peminjaman->update([
                'status' => 'approved',
                'barang_unit_id' => $firstUnitId,
                'jumlah' => 1,
            ]);
            $peminjaman->barang->decrement('jumlah_stok');

            // 2. Create new records for REMAINING approved units
            foreach ($unitIds as $unitId) {
                $newPeminjaman = $peminjaman->replicate(['items']);
                $newPeminjaman->kode = Peminjaman::generateKode();
                $newPeminjaman->barang_unit_id = $unitId;
                $newPeminjaman->jumlah = 1;
                $newPeminjaman->status = 'approved';
                $newPeminjaman->created_at = $peminjaman->created_at;
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
                $rejectedPeminjaman->keterangan_penolakan = 'Sebagian permintaan ditolak karena unit tidak dipilih oleh admin.';
                $rejectedPeminjaman->created_at = $peminjaman->created_at;
                $rejectedPeminjaman->save();
            }

            \App\Helpers\ActivityLogger::log('Approve Peminjaman', 'Menyetujui sebagian/seluruh peminjaman: ' . $peminjaman->kode . ' (' . $approvedCount . ' approved, ' . $rejectedCount . ' rejected)', $peminjaman);

        } else {
            // All Rejected (approvedCount == 0) - Maybe via unchecking all?
            // Usually uncheck all shouldn't be allowed in approve form logic, but handle it just in case OR if logic allows empty selection to means Reject All.
            // However, validate says 'required|array', so empty array might fail validation if not handling empty. 
            // But if user submits empty... request->validate 'required' will block.
            // So this block might be unreachable if we enforce at least 1 selection in UI?
            // Let's assume user MUST select at least 1 to be "Approved". If they want to reject all, use Reject button.
            return back()->with('error', 'Pilih setidaknya satu unit untuk disetujui.');
        }

        return redirect()->route('admin.peminjaman.show', $peminjaman->id)->with('success', 'Peminjaman diproses. ' . $approvedCount . ' disetujui, ' . $rejectedCount . ' ditolak.');
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



        \App\Helpers\ActivityLogger::log('Reject Peminjaman', 'Menolak peminjaman: ' . $peminjaman->kode, $peminjaman);

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



        \App\Helpers\ActivityLogger::log('Activate Peminjaman', 'Mengubah status menjadi Aktif (Barang Diambil): ' . $peminjaman->kode, $peminjaman);

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
        $url = route('admin.peminjaman.bukti', $id);

        // Render QR as SVG Base64 (Avoids ImageMagick dependency)
        $qrRaw = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(150)->generate($url);
        $qrCodeImage = 'data:image/svg+xml;base64,' . base64_encode($qrRaw);

        $pdf = Pdf::loadView('admin.peminjaman.bukti', compact('peminjaman', 'qrCodeImage'));
        $pdf->setPaper('A4', 'portrait');

        $pdf->render();
        // Auto-print removed upon request
        // $canvas = $pdf->getDomPDF()->getCanvas();
        // if (method_exists($canvas, 'get_cpdf')) {
        //     $canvas->get_cpdf()->addJavaScript('this.print({bUI: true, bSilent: false, bShrinkToFit: true});');
        // }

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

        return view('admin.peminjaman.return', compact('peminjaman'));
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
            $isLost = false;

            if ($request->kondisi == 'rusak_ringan') {
                $statusUnit = 'maintenance';
            } elseif ($request->kondisi == 'rusak_berat') {
                $statusUnit = 'rusak';
            } elseif ($request->kondisi == 'hilang') {
                $statusUnit = 'rusak';
                $isLost = true; // Flag as lost for dedicated tracking
            }

            $unit->update([
                'status' => $statusUnit,
                'is_lost' => $isLost,
            ]);

            // Increment generic stock ONLY if good condition
            if ($request->kondisi == 'baik') {
                $peminjaman->barang->increment('jumlah_stok');
            }
        }

        \App\Helpers\ActivityLogger::log('Pengembalian Barang', 'Menerima pengembalian: ' . $peminjaman->kode . ' Kondisi: ' . $request->kondisi, $peminjaman);

        return redirect()->route('admin.peminjaman.index')->with('success', 'Barang berhasil dikembalikan.');
    }
    public function redirectByKode($kode)
    {
        $peminjaman = Peminjaman::where('kode', $kode)->firstOrFail();
        return redirect()->route('admin.peminjaman.bukti', $peminjaman->id);
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

        return view('admin.peminjaman.inspect', compact(
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

        return redirect()->route('admin.peminjaman.show', $id)->with('success', $message);
    }

    public function approveBulk(Request $request, $kode)
    {
        if (now()->isWeekend()) {
            return back()->with('error', 'Layanan tutup pada akhir pekan.');
        }

        $items = Peminjaman::where('kode', $kode)->where('status', 'pending')->get();
        if ($items->isEmpty())
            return back()->with('error', 'Tidak ada item pending.');

        $count = 0;
        foreach ($items as $item) {
            /** @var \App\Models\Peminjaman $item */
            if (!$item->barang_unit_id) {
                // Auto assign distinct unit
                $unit = BarangUnit::where('barang_id', $item->barang_id)
                    ->where('status', 'aktif')
                    ->whereDoesntHave('peminjaman', function ($q) {
                        $q->whereIn('status', ['approved', 'active']);
                    })
                    ->first();

                if ($unit) {
                    $item->update(['barang_unit_id' => $unit->id, 'status' => 'approved']);
                    // $item->barangUnit()->update(['status' => 'dipinjam']); // Optionally update unit status if your logic requires
                    $count++;
                }
            } else {
                $item->update(['status' => 'approved']);
                $count++;
            }
        }

        return back()->with('success', "$count item berhasil disetujui.");
    }

    public function activateBulk(Request $request, $kode)
    {
        $items = Peminjaman::where('kode', $kode)->where('status', 'approved')->with('barangUnit')->get();

        foreach ($items as $item) {
            /** @var \App\Models\Peminjaman $item */
            // Save Inspection if data exists
            if ($request->has("checklist.$item->id")) {
                $checklistData = $request->input("checklist.$item->id");

                \App\Models\Inspection::create([
                    'peminjaman_id' => $item->id,
                    'barang_unit_id' => $item->barang_unit_id,
                    'type' => 'pre_borrow',
                    'inspector_id' => \Illuminate\Support\Facades\Auth::id(),
                    'inspected_at' => now(),
                    'checklist_data' => $checklistData,
                    'notes' => $request->input("notes.$item->id"),
                    'has_damage' => false
                ]);
            }

            $item->update(['status' => 'active']);
        }

        return back()->with('success', 'Barang berhasil diserahkan (Aktif).');
    }
}
