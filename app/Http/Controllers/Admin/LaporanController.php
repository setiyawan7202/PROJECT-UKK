<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Pengaduan;
use App\Models\BarangUnit;
use Barryvdh\DomPDF\Facade\Pdf;

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

        $pdf = Pdf::loadView('admin.laporan.pdf_barang', [
            'data' => $data,
            'date' => now()->format('Y-m-d'),
        ]);

        return $pdf->stream('laporan-aset-barang.pdf');
    }
}
