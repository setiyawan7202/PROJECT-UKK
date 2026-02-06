<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Pengaduan;
use App\Models\BarangUnit;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    /**
     * Display Report Dashboard
     */
    public function index()
    {
        return view('admin.laporan.index');
    }

    /**
     * Generate Peminjaman Report (PDF)
     */
    public function peminjaman(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $data = Peminjaman::with(['user', 'barang', 'barangUnit'])
            ->whereBetween('created_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59'])
            ->orderBy('created_at', 'asc')
            ->get();

        $pdf = Pdf::loadView('admin.laporan.pdf_peminjaman', [
            'data' => $data,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return $pdf->stream('laporan-peminjaman.pdf');
    }

    /**
     * Generate Pengaduan Report (PDF)
     */
    public function pengaduan(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $data = Pengaduan::with(['user'])
            ->whereBetween('created_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59'])
            ->orderBy('created_at', 'asc')
            ->get();

        $pdf = Pdf::loadView('admin.laporan.pdf_pengaduan', [
            'data' => $data,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return $pdf->stream('laporan-pengaduan.pdf');
    }

    /**
     * Generate Asset Health Report (PDF)
     */
    public function barang()
    {
        // Get units grouped by status and barang
        $data = BarangUnit::with('barang')
            ->orderBy('barang_id')
            ->orderBy('status')
            ->get();

        // Tier 1 Analytics: Failed/Lost items
        $damagedItems = BarangUnit::whereIn('status', ['rusak', 'hilang'])->with('barang')->get();

        // Tier 1 Analytics: Most Problematic Items
        $mostProblematic = \App\Models\Pengembalian::select('barang_id', DB::raw('count(*) as total_rusak'))
            ->join('peminjaman', 'pengembalian.peminjaman_id', '=', 'peminjaman.id')
            ->where('pengembalian.kondisi', '!=', 'baik')
            ->groupBy('barang_id')
            ->orderByDesc('total_rusak')
            ->limit(10)
            ->get();

        // Fix: accessing barang name from relation
        $mostProblematic->transform(function ($item) {
            $barang = \App\Models\Barang::find($item->barang_id);
            $item->nama_barang = $barang ? $barang->nama_barang : 'Unknown';
            return $item;
        });

        $pdf = Pdf::loadView('admin.laporan.pdf_barang', [
            'data' => $data,
            'damagedItems' => $damagedItems,
            'mostProblematic' => $mostProblematic,
            'date' => now()->format('Y-m-d'),
        ]);

        return $pdf->stream('laporan-aset-barang.pdf');
    }
}
