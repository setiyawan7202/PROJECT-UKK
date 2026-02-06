@extends('layouts.staff')

@section('title', 'Dashboard Petugas')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6 lg:mb-8">
        <div>
            <h1 class="text-xl lg:text-2xl font-bold text-gray-900">Dashboard Staff</h1>
            <p class="text-sm lg:text-base text-gray-500">Selamat datang,
                {{ Auth::user()->nama_lengkap ?? Auth::user()->email }}
            </p>
        </div>
        <span class="hidden lg:inline-flex px-3 py-1 bg-gray-700 text-white text-sm font-medium rounded-full">Staff</span>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-6 mb-6 lg:mb-8">
        <div class="bg-white rounded-xl lg:rounded-2xl border border-gray-100 p-4 lg:p-6">
            <div class="flex flex-col lg:flex-row lg:items-center gap-2 lg:gap-4">
                <div
                    class="w-10 h-10 lg:w-12 lg:h-12 bg-gray-100 rounded-lg lg:rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 lg:w-6 lg:h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <div>
                    <p class="text-xl lg:text-2xl font-bold">{{ $stats['total_barang'] }}</p>
                    <p class="text-xs lg:text-sm text-gray-500">Total Aset</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl lg:rounded-2xl border border-gray-100 p-4 lg:p-6">
            <div class="flex flex-col lg:flex-row lg:items-center gap-2 lg:gap-4">
                <div
                    class="w-10 h-10 lg:w-12 lg:h-12 bg-gray-100 rounded-lg lg:rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 lg:w-6 lg:h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xl lg:text-2xl font-bold">{{ $stats['total_kategori'] }}</p>
                    <p class="text-xs lg:text-sm text-gray-500">Kategori</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl lg:rounded-2xl border border-gray-100 p-4 lg:p-6">
            <div class="flex flex-col lg:flex-row lg:items-center gap-2 lg:gap-4">
                <div
                    class="w-10 h-10 lg:w-12 lg:h-12 bg-gray-100 rounded-lg lg:rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 lg:w-6 lg:h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div>
                    <p class="text-xl lg:text-2xl font-bold">{{ $stats['total_ruangan'] }}</p>
                    <p class="text-xs lg:text-sm text-gray-500">Ruangan</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl lg:rounded-2xl border border-gray-100 p-4 lg:p-6">
            <div class="flex flex-col lg:flex-row lg:items-center gap-2 lg:gap-4">
                <div
                    class="w-10 h-10 lg:w-12 lg:h-12 bg-gray-100 rounded-lg lg:rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 lg:w-6 lg:h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xl lg:text-2xl font-bold">{{ $stats['total_user'] }}</p>
                    <p class="text-xs lg:text-sm text-gray-500">Total User</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 mb-6 lg:mb-8">
        <div class="bg-white p-4 lg:p-6 rounded-xl lg:rounded-2xl border border-gray-100">
            <div class="flex flex-col sm:flex-row justify-between items-center mb-4 gap-4">
                <h3 class="font-bold text-gray-900 text-sm lg:text-base">Statistik Peminjaman & Pengaduan</h3>
                <div class="flex gap-2 bg-gray-50 p-1 rounded-lg">
                    <button onclick="updateChart('daily')"
                        class="text-xs px-3 py-1.5 rounded-md font-medium transition chart-filter active-filter bg-white text-blue-600 shadow-sm"
                        data-filter="daily">Harian</button>
                    <button onclick="updateChart('weekly')"
                        class="text-xs px-3 py-1.5 rounded-md font-medium text-gray-500 hover:text-gray-900 transition chart-filter"
                        data-filter="weekly">Mingguan</button>
                    <button onclick="updateChart('monthly')"
                        class="text-xs px-3 py-1.5 rounded-md font-medium text-gray-500 hover:text-gray-900 transition chart-filter"
                        data-filter="monthly">Bulanan</button>
                </div>
            </div>
            <div class="relative h-64 lg:h-80 w-full">
                <canvas id="dashboardChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Peminjaman & Pengaduan Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-6 mb-6 lg:mb-8">
        <div class="bg-white rounded-xl lg:rounded-2xl border border-gray-100 p-4 lg:p-6">
            <div class="flex flex-col lg:flex-row lg:items-center gap-2 lg:gap-4">
                <div
                    class="w-10 h-10 lg:w-12 lg:h-12 bg-gray-100 rounded-lg lg:rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 lg:w-6 lg:h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xl lg:text-2xl font-bold">{{ $stats['pending_peminjaman'] }}</p>
                    <p class="text-xs lg:text-sm text-gray-500">Peminjaman Pending</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl lg:rounded-2xl border border-gray-100 p-4 lg:p-6">
            <div class="flex flex-col lg:flex-row lg:items-center gap-2 lg:gap-4">
                <div
                    class="w-10 h-10 lg:w-12 lg:h-12 bg-gray-100 rounded-lg lg:rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 lg:w-6 lg:h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xl lg:text-2xl font-bold">{{ $stats['approved_peminjaman'] }}</p>
                    <p class="text-xs lg:text-sm text-gray-500">Peminjaman Diterima</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl lg:rounded-2xl border border-gray-100 p-4 lg:p-6">
            <div class="flex flex-col lg:flex-row lg:items-center gap-2 lg:gap-4">
                <div
                    class="w-10 h-10 lg:w-12 lg:h-12 bg-gray-100 rounded-lg lg:rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 lg:w-6 lg:h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xl lg:text-2xl font-bold">{{ $stats['pending_pengaduan'] }}</p>
                    <p class="text-xs lg:text-sm text-gray-500">Pengaduan Pending</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl lg:rounded-2xl border border-gray-100 p-4 lg:p-6">
            <div class="flex flex-col lg:flex-row lg:items-center gap-2 lg:gap-4">
                <div
                    class="w-10 h-10 lg:w-12 lg:h-12 bg-gray-100 rounded-lg lg:rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 lg:w-6 lg:h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div>
                    <p class="text-xl lg:text-2xl font-bold">{{ $stats['selesai_pengaduan'] }}</p>
                    <p class="text-xs lg:text-sm text-gray-500">Pengaduan Selesai</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 lg:gap-6 mb-6 lg:mb-8">
        <a href="{{ route('staff.peminjaman.index') }}"
            class="bg-white rounded-xl lg:rounded-2xl border border-gray-100 p-4 lg:p-6 hover:shadow-lg transition block">
            <div
                class="w-10 h-10 lg:w-12 lg:h-12 bg-gray-100 rounded-lg lg:rounded-xl flex items-center justify-center mb-3 lg:mb-4">
                <svg class="w-5 h-5 lg:w-6 lg:h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                </svg>
            </div>
            <h3 class="font-semibold text-gray-900 text-sm lg:text-base mb-1">Peminjaman</h3>
            <p class="text-xs lg:text-sm text-gray-500">Kelola peminjaman</p>
        </a>

        <a href="{{ route('staff.pengaduan.index') }}"
            class="bg-white rounded-xl lg:rounded-2xl border border-gray-100 p-4 lg:p-6 hover:shadow-lg transition block">
            <div
                class="w-10 h-10 lg:w-12 lg:h-12 bg-gray-100 rounded-lg lg:rounded-xl flex items-center justify-center mb-3 lg:mb-4">
                <svg class="w-5 h-5 lg:w-6 lg:h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <h3 class="font-semibold text-gray-900 text-sm lg:text-base mb-1">Pengaduan</h3>
            <p class="text-xs lg:text-sm text-gray-500">Kelola pengaduan</p>
        </a>

        <a href="{{ route('staff.barang.index') }}"
            class="bg-white rounded-xl lg:rounded-2xl border border-gray-100 p-4 lg:p-6 hover:shadow-lg transition block">
            <div
                class="w-10 h-10 lg:w-12 lg:h-12 bg-gray-100 rounded-lg lg:rounded-xl flex items-center justify-center mb-3 lg:mb-4">
                <svg class="w-5 h-5 lg:w-6 lg:h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
            </div>
            <h3 class="font-semibold text-gray-900 text-sm lg:text-base mb-1">Lihat Aset</h3>
            <p class="text-xs lg:text-sm text-gray-500">Lihat daftar aset</p>
        </a>

        <a href="{{ route('staff.kategori.index') }}"
            class="bg-white rounded-xl lg:rounded-2xl border border-gray-100 p-4 lg:p-6 hover:shadow-lg transition block">
            <div
                class="w-10 h-10 lg:w-12 lg:h-12 bg-gray-100 rounded-lg lg:rounded-xl flex items-center justify-center mb-3 lg:mb-4">
                <svg class="w-5 h-5 lg:w-6 lg:h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                </svg>
            </div>
            <h3 class="font-semibold text-gray-900 text-sm lg:text-base mb-1">Lihat Kategori</h3>
            <p class="text-xs lg:text-sm text-gray-500">Lihat daftar kategori</p>
        </a>

        <a href="{{ route('staff.ruangan.index') }}"
            class="bg-white rounded-xl lg:rounded-2xl border border-gray-100 p-4 lg:p-6 hover:shadow-lg transition block">
            <div
                class="w-10 h-10 lg:w-12 lg:h-12 bg-gray-100 rounded-lg lg:rounded-xl flex items-center justify-center mb-3 lg:mb-4">
                <svg class="w-5 h-5 lg:w-6 lg:h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <h3 class="font-semibold text-gray-900 text-sm lg:text-base mb-1">Lihat Ruangan</h3>
            <p class="text-xs lg:text-sm text-gray-500">Lihat daftar ruangan</p>
        </a>

        <a href="{{ route('staff.users.index') }}"
            class="bg-white rounded-xl lg:rounded-2xl border border-gray-100 p-4 lg:p-6 hover:shadow-lg transition block">
            <div
                class="w-10 h-10 lg:w-12 lg:h-12 bg-gray-100 rounded-lg lg:rounded-xl flex items-center justify-center mb-3 lg:mb-4">
                <svg class="w-5 h-5 lg:w-6 lg:h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
            <h3 class="font-semibold text-gray-900 text-sm lg:text-base mb-1">Kelola User</h3>
            <p class="text-xs lg:text-sm text-gray-500">Kelola pengguna Guru/Siswa</p>
        </a>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-xl lg:rounded-2xl border border-gray-100 p-4 lg:p-6 mb-6">
        <h2 class="font-semibold text-gray-900 text-sm lg:text-base mb-4">Aktivitas Terbaru</h2>
        <div class="space-y-4">
            @forelse($recentActivities as $activity)
                <div class="flex items-start gap-4 p-4 rounded-xl bg-gray-50 hover:bg-gray-100 transition">
                    <div
                        class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center shrink-0 font-bold">
                        {{ substr($activity->user->nama_lengkap ?? $activity->user->email ?? 'S', 0, 1) }}
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">{{ $activity->description }}</p>
                        <p class="text-xs text-gray-500 mt-1">
                            Oleh: <span
                                class="font-medium text-gray-700">{{ $activity->user->nama_lengkap ?? $activity->user->email ?? 'System' }}</span>
                            &bull; {{ $activity->created_at->diffForHumans() }}
                        </p>
                    </div>
                    <span
                        class="px-2 py-1 text-[10px] font-medium uppercase tracking-wide rounded-full 
                                                                                                                        {{ $activity->action == 'create' ? 'bg-green-100 text-green-700' :
                ($activity->action == 'delete' ? 'bg-red-100 text-red-700' :
                    ($activity->action == 'restore' ? 'bg-yellow-100 text-yellow-700' : 'bg-blue-100 text-blue-700')) }}">
                        {{ $activity->action }}
                    </span>
                </div>
            @empty
                <div class="text-center py-6 text-gray-500">
                    <p class="text-sm">Belum ada aktivitas terbaru.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Peminjaman Terbaru -->
    <div class="bg-white rounded-xl lg:rounded-2xl border border-gray-100 p-4 lg:p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="font-semibold text-gray-900 text-sm lg:text-base">Peminjaman Terbaru</h2>
            <a href="{{ route('staff.peminjaman.index') }}"
                class="text-sm text-blue-600 hover:text-blue-700 font-medium">Lihat Semua</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="text-gray-500 border-b border-gray-100">
                        <th class="px-4 py-3 font-medium text-xs uppercase">Peminjam</th>
                        <th class="px-4 py-3 font-medium text-xs uppercase">Barang</th>
                        <th class="px-4 py-3 font-medium text-xs uppercase">Status</th>
                        <th class="px-4 py-3 font-medium text-xs uppercase text-right">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($recentPeminjamans as $pinjam)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-4 py-3">
                                        <span class="block font-medium text-gray-900">
                                            {{ $pinjam->user->nama_lengkap ?? $pinjam->user->email }}
                                        </span>
                                        <span class="text-xs text-gray-500">{{ $pinjam->user->role }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="block text-gray-900">{{ $pinjam->barang->nama_barang ?? '-' }}</span>
                                        <span class="text-xs text-gray-500">{{ $pinjam->jumlah }} Unit</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span
                                            class="px-2 py-1 text-xs font-medium rounded-full
                                                                                                                                                                                                                                    {{ $pinjam->status == 'approved' ? 'bg-green-100 text-green-700' :
                        ($pinjam->status == 'rejected' ? 'bg-red-100 text-red-700' :
                            ($pinjam->status == 'returned' ? 'bg-blue-100 text-blue-700' : 'bg-yellow-100 text-yellow-700')) }}">
                                            {{ ucfirst($pinjam->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-right text-gray-500">
                                        {{ $pinjam->created_at->diffForHumans() }}
                                    </td>
                                </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                                Belum ada data peminjaman.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Pengaduan -->
    <div class="bg-white rounded-xl lg:rounded-2xl border border-gray-100 p-4 lg:p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-semibold text-gray-900 text-sm lg:text-base">Pengaduan Terbaru</h2>
            <a href="{{ route('staff.pengaduan.index') }}" class="text-sm text-blue-600 hover:text-blue-700">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[600px] text-left text-sm">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 border-b border-gray-100">
                        <th class="px-4 py-3 font-medium text-xs uppercase">Pelapor</th>
                        <th class="px-4 py-3 font-medium text-xs uppercase">Aset</th>
                        <th class="px-4 py-3 font-medium text-xs uppercase">Status</th>
                        <th class="px-4 py-3 font-medium text-xs uppercase text-right">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($recentPengaduans as $pengaduan)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-4 py-3">
                                        <span class="block font-medium text-gray-900">
                                            {{ $pengaduan->user->nama_lengkap ?? $pengaduan->user->email }}
                                        </span>
                                        <span class="text-xs text-gray-500">{{ $pengaduan->user->role }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span
                                            class="block text-gray-900">{{ $pengaduan->barangUnit->barang->nama_barang ?? '-' }}</span>
                                        <span class="text-xs text-gray-500">{{ $pengaduan->barangUnit->kode_unit ?? '-' }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span
                                            class="px-2 py-1 text-xs font-medium rounded-full
                                                                                                                                                                                    {{ $pengaduan->status == 'selesai' ? 'bg-green-100 text-green-700' :
                        ($pengaduan->status == 'ditolak' ? 'bg-red-100 text-red-700' :
                            ($pengaduan->status == 'diproses' ? 'bg-blue-100 text-blue-700' : 'bg-yellow-100 text-yellow-700')) }}">
                                            {{ ucfirst($pengaduan->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-right text-gray-500">
                                        {{ $pengaduan->created_at->diffForHumans() }}
                                    </td>
                                </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                                Belum ada data pengaduan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let myChart = null;

        document.addEventListener('DOMContentLoaded', function () {
            updateChart('daily');
        });

        function updateChart(filter) {
            // Update buttons styling
            document.querySelectorAll('.chart-filter').forEach(btn => {
                btn.classList.remove('bg-white', 'text-blue-600', 'shadow-sm', 'active-filter');
                btn.classList.add('text-gray-500', 'hover:text-gray-900');
                if (btn.dataset.filter === filter) {
                    btn.classList.remove('text-gray-500', 'hover:text-gray-900');
                    btn.classList.add('bg-white', 'text-blue-600', 'shadow-sm', 'active-filter');
                }
            });

            // Fetch Data
            fetch(`{{ route('staff.dashboard.chart') }}?filter=${filter}`)
                .then(response => response.json())
                .then(data => {
                    const ctx = document.getElementById('dashboardChart').getContext('2d');

                    if (myChart) {
                        myChart.destroy();
                    }

                    myChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: data.labels,
                            datasets: [
                                {
                                    label: 'Peminjaman',
                                    data: data.peminjaman,
                                    borderColor: '#3b82f6', // Blue
                                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                    borderWidth: 2,
                                    tension: 0.4,
                                    fill: true,
                                    pointRadius: 3,
                                    pointHoverRadius: 6
                                },
                                {
                                    label: 'Pengaduan',
                                    data: data.pengaduan,
                                    borderColor: '#ef4444', // Red
                                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                                    borderWidth: 2,
                                    tension: 0.4,
                                    fill: true,
                                    pointRadius: 3,
                                    pointHoverRadius: 6
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'top',
                                    labels: {
                                        usePointStyle: true,
                                        boxWidth: 8
                                    }
                                },
                                tooltip: {
                                    mode: 'index',
                                    intersect: false,
                                    backgroundColor: 'rgba(255, 255, 255, 0.9)',
                                    titleColor: '#1f2937',
                                    bodyColor: '#4b5563',
                                    borderColor: '#e5e7eb',
                                    borderWidth: 1,
                                    padding: 10,
                                    displayColors: true
                                }
                            },
                            scales: {
                                x: {
                                    grid: {
                                        display: false
                                    },
                                    ticks: {
                                        font: {
                                            size: 11
                                        }
                                    }
                                },
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        borderDash: [2, 2],
                                        color: '#f3f4f6'
                                    },
                                    ticks: {
                                        stepSize: 1,
                                        font: {
                                            size: 11
                                        }
                                    }
                                }
                            },
                            interaction: {
                                mode: 'nearest',
                                axis: 'x',
                                intersect: false
                            }
                        }
                    });
                });
        }
    </script>
@endsection