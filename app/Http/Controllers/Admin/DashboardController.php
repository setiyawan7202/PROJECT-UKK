<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Auth as User; // Using Auth model as User
use App\Models\Barang;
use App\Models\Peminjaman;
use App\Models\Activity;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_barang' => Barang::count(),
            'total_user' => User::count(),
            'total_peminjaman' => Peminjaman::count(),
            'pending_peminjaman' => Peminjaman::where('status', 'pending')->count(),
        ];

        // Fetch recent activities (limit 5)
        $recentActivities = Activity::with('user')->latest()->take(5)->get();

        // Fetch recent Peminjaman with related data (limit 5)
        $recentPeminjamans = Peminjaman::with(['user.siswa', 'user.guru', 'barang'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.index', compact('stats', 'recentActivities', 'recentPeminjamans'));
    }
}
