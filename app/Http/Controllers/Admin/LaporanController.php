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

        $pdf = Pdf::loadView('pdf.laporan-peminjaman', [
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

        $pdf = Pdf::loadView('pdf.laporan-pengaduan', [
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

        $pdf = Pdf::loadView('pdf.laporan-barang', [
            'data' => $data,
            'damagedItems' => $damagedItems,
            'mostProblematic' => $mostProblematic,
            'date' => now()->format('Y-m-d'),
        ]);

        return $pdf->stream('laporan-aset-barang.pdf');
    }

    /**
     * Asset Health Dashboard (Dedicated Page)
     */
    public function assetHealth()
    {
        // ... (existing code)
        // Damaged Items (Rusak & Hilang)
        $damagedItems = BarangUnit::whereIn('status', ['rusak'])->with('barang.kategori')->get();
        $lostItems = BarangUnit::where('is_lost', true)->with('barang.kategori')->get();

        // Top 10 Most Damaged Assets
        $topDamagedAssets = DB::table('pengembalian')
            ->join('peminjaman', 'pengembalian.peminjaman_id', '=', 'peminjaman.id')
            ->join('barang', 'peminjaman.barang_id', '=', 'barang.id')
            ->whereIn('pengembalian.kondisi', ['rusak_ringan', 'rusak_berat', 'hilang'])
            ->select('barang.id', 'barang.nama_barang', 'barang.kode', DB::raw('COUNT(*) as total_rusak'))
            ->groupBy('barang.id', 'barang.nama_barang', 'barang.kode')
            ->orderByDesc('total_rusak')
            ->limit(10)
            ->get();

        // Summary Statistics
        $totalAssets = BarangUnit::count();
        $activeAssets = BarangUnit::where('status', 'aktif')->count();
        $damagedCount = BarangUnit::where('status', 'rusak')->where('is_lost', false)->count();
        $lostCount = BarangUnit::where('is_lost', true)->count();

        // Assets by Condition
        $assetsByCondition = BarangUnit::select('kondisi', DB::raw('COUNT(*) as total'))
            ->groupBy('kondisi')
            ->get()
            ->pluck('total', 'kondisi')
            ->toArray();

        // Recent Damage History (Last 30 days)
        $recentDamages = DB::table('pengembalian')
            ->join('peminjaman', 'pengembalian.peminjaman_id', '=', 'peminjaman.id')
            ->join('barang', 'peminjaman.barang_id', '=', 'barang.id')
            ->join('users', 'peminjaman.user_id', '=', 'users.id')
            ->select('pengembalian.*', 'barang.nama_barang', 'users.username as nama_lengkap')
            ->whereIn('pengembalian.kondisi', ['rusak_ringan', 'rusak_berat', 'hilang'])
            ->where('pengembalian.created_at', '>=', now()->subDays(30))
            ->orderByDesc('pengembalian.created_at')
            ->limit(20)
            ->get();

        // Damage Trend (Last 6 months)
        $damageTrend = DB::table('pengembalian')
            ->whereIn('kondisi', ['rusak_ringan', 'rusak_berat', 'hilang'])
            ->where('created_at', '>=', now()->subMonths(6))
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as total")
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('total', 'month')
            ->toArray();

        return view('admin.laporan.asset-health', compact(
            'damagedItems',
            'lostItems',
            'topDamagedAssets',
            'totalAssets',
            'activeAssets',
            'damagedCount',
            'lostCount',
            'assetsByCondition',
            'recentDamages',
            'damageTrend'
        ));
    }

    /**
     * Analytics & Location Dashboard
     */
    public function analytics()
    {
        $service = new \App\Services\AssetAnalyticsService();
        $risks = $service->getAssetRisks(50);
        $locations = $service->getLocationStats();

        return view('admin.laporan.analytics', compact('risks', 'locations'));
    }
}
