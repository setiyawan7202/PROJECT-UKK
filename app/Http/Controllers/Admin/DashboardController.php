<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Auth as User;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Ruangan;
use App\Models\Peminjaman;
use App\Models\Pengaduan;
use App\Models\Activity;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_barang' => Barang::count(),
            'total_kategori' => Kategori::count(),
            'total_ruangan' => Ruangan::count(),
            'total_user' => User::count(),
            'pending_peminjaman' => Peminjaman::where('status', 'pending')->count(),
            'approved_peminjaman' => Peminjaman::where('status', 'approved')->count(),
            'pending_pengaduan' => Pengaduan::where('status', 'pending')->count(),
            'selesai_pengaduan' => Pengaduan::where('status', 'selesai')->count(),
        ];

        // Fetch recent activities (limit 5)
        $recentActivities = Activity::with('user')->latest()->take(5)->get();

        // Fetch recent Peminjaman with related data (limit 5)
        $recentPeminjamans = Peminjaman::with(['user.siswa', 'user.guru', 'barang'])
            ->latest()
            ->take(5)
            ->get();

        // Fetch recent Pengaduan with related data (limit 5)
        $recentPengaduans = Pengaduan::with(['user', 'barangUnit.barang'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.index', compact('stats', 'recentActivities', 'recentPeminjamans', 'recentPengaduans'));
    }
    public function getChartData(Request $request)
    {
        $filter = $request->query('filter', 'daily');

        $labels = [];
        $peminjamanData = [];
        $pengaduanData = [];

        if ($filter == 'daily') {
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i)->format('Y-m-d');
                $labels[] = now()->subDays($i)->format('d M');
                $peminjamanData[] = Peminjaman::whereDate('created_at', $date)->count();
                $pengaduanData[] = Pengaduan::whereDate('created_at', $date)->count();
            }
        } elseif ($filter == 'weekly') {
            for ($i = 3; $i >= 0; $i--) {
                $start = now()->subWeeks($i)->startOfWeek();
                $end = now()->subWeeks($i)->endOfWeek();
                $labels[] = $start->format('d M') . ' - ' . $end->format('d M');
                $peminjamanData[] = Peminjaman::whereBetween('created_at', [$start, $end])->count();
                $pengaduanData[] = Pengaduan::whereBetween('created_at', [$start, $end])->count();
            }
        } elseif ($filter == 'monthly') {
            for ($i = 11; $i >= 0; $i--) {
                $month = now()->subMonths($i);
                $labels[] = $month->format('M Y');
                $peminjamanData[] = Peminjaman::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)->count();
                $pengaduanData[] = Pengaduan::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)->count();
            }
        }

        return response()->json([
            'labels' => $labels,
            'peminjaman' => $peminjamanData,
            'pengaduan' => $pengaduanData
        ]);
    }
}
