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

    /**
     * Approve the specified loan request.
     */
    public function approve(Request $request, $id)
    {
        $request->validate([
            'barang_unit_id' => 'required|exists:barang_unit,id',
        ]);

        $peminjaman = Peminjaman::findOrFail($id);

        if ($peminjaman->status !== 'pending') {
            return back()->with('error', 'Hanya peminjaman pending yang bisa disetujui.');
        }

        // Verify unit availability
        $unit = BarangUnit::findOrFail($request->barang_unit_id);
        if ($unit->status !== 'aktif') {
            return back()->with('error', 'Unit ini sedang tidak tersedia (Rusak/Maintenance).');
        }

        // Is unit currently borrowed?
        $isBorrowed = Peminjaman::where('barang_unit_id', $unit->id)
            ->whereIn('status', ['active', 'approved'])
            ->exists();

        if ($isBorrowed) {
            return back()->with('error', 'Unit ini sedang dipinjam atau sudah disetujui untuk peminjaman lain.');
        }

        $peminjaman->update([
            'status' => 'approved',
            'barang_unit_id' => $request->barang_unit_id,
        ]);

        // Reduce Generic Stock from Barang? 
        // Logic: When approved, it's reserved, so stock decreases.
        $peminjaman->barang->decrement('jumlah_stok');

        return back()->with('success', 'Peminjaman disetujui. Unit telah dialokasikan.');
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

        return view('admin.peminjaman.bukti', compact('peminjaman'));
    }

    /**
     * Show Return Form
     */
    public function returnForm($id)
    {
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
        $request->validate([
            'tgl_kembali' => 'required|date',
            'kondisi' => 'required|in:baik,rusak_ringan,rusak_berat,hilang',
            'denda' => 'nullable|numeric|min:0',
            'keterangan' => 'nullable|string',
            'foto' => 'nullable|image|max:2048',
        ]);

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

        return redirect()->route('admin.peminjaman.index')->with('success', 'Barang berhasil dikembalikan.');
    }
}
