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
        $query = Peminjaman::with(['user', 'barang', 'barangUnit'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $peminjaman = $query->paginate(10);
        return view('admin.peminjaman.index', compact('peminjaman'));
    }

    public function show($id)
    {
        $peminjaman = Peminjaman::with(['user', 'barang', 'barangUnit', 'pengembalian'])->findOrFail($id);
        return view('admin.peminjaman.show', compact('peminjaman'));
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
            'barang_unit_ids' => 'required|array|size:' . $peminjaman->jumlah,
            'barang_unit_ids.*' => 'required|exists:barang_unit,id|distinct',
        ]);

        $unitIds = $request->barang_unit_ids;

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

            $newPeminjaman->barang->decrement('jumlah_stok');
        }



        \App\Helpers\ActivityLogger::log('Approve Peminjaman', 'Menyetujui peminjaman: ' . $peminjaman->kode . ' (' . count($unitIds) . ' unit)', $peminjaman);

        return redirect()->route('admin.peminjaman.index')->with('success', 'Peminjaman disetujui. ' . count($request->barang_unit_ids) . ' unit telah dialokasikan.');
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
            return back()->withInput()->with('error', 'Tanggal pengembalian tidak boleh jatuh pada hari Sabtu atau Minggu.');
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

        \App\Helpers\ActivityLogger::log('Pengembalian Barang', 'Menerima pengembalian: ' . $peminjaman->kode . ' Kondisi: ' . $request->kondisi, $peminjaman);

        return redirect()->route('admin.peminjaman.index')->with('success', 'Barang berhasil dikembalikan.');
    }
    public function redirectByKode($kode)
    {
        $peminjaman = Peminjaman::where('kode', $kode)->firstOrFail();
        return redirect()->route('admin.peminjaman.bukti', $peminjaman->id);
    }
}
