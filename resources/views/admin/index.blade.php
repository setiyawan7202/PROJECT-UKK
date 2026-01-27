@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6 lg:mb-8">
        <div>
            <h1 class="text-xl lg:text-2xl font-bold text-gray-900">Dashboard Admin</h1>
            <p class="text-sm lg:text-base text-gray-500">Selamat datang,
                {{ Auth::user()->nama_lengkap ?? Auth::user()->email }}
            </p>
        </div>
        <div class="hidden lg:flex items-center gap-3">
            <span class="px-3 py-1 bg-black text-white text-sm font-medium rounded-full">Admin</span>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-6 mb-6 lg:mb-8">
        <div class="bg-white rounded-xl lg:rounded-2xl border border-gray-100 p-4 lg:p-6 card-hover">
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
                    <p class="text-xs lg:text-sm text-gray-500">Total Barang</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl lg:rounded-2xl border border-gray-100 p-4 lg:p-6 card-hover">
            <div class="flex flex-col lg:flex-row lg:items-center gap-2 lg:gap-4">
                <div
                    class="w-10 h-10 lg:w-12 lg:h-12 bg-gray-100 rounded-lg lg:rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 lg:w-6 lg:h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xl lg:text-2xl font-bold">{{ $stats['total_user'] }}</p>
                    <p class="text-xs lg:text-sm text-gray-500">Total User</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl lg:rounded-2xl border border-gray-100 p-4 lg:p-6 card-hover">
            <div class="flex flex-col lg:flex-row lg:items-center gap-2 lg:gap-4">
                <div
                    class="w-10 h-10 lg:w-12 lg:h-12 bg-gray-100 rounded-lg lg:rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 lg:w-6 lg:h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <div>
                    <p class="text-xl lg:text-2xl font-bold">{{ $stats['total_peminjaman'] }}</p>
                    <p class="text-xs lg:text-sm text-gray-500">Peminjaman</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl lg:rounded-2xl border border-gray-100 p-4 lg:p-6 card-hover">
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
                    <p class="text-xs lg:text-sm text-gray-500">Pending</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 lg:gap-6 mb-6 lg:mb-8">
        <a href="{{ route('admin.barang.create') }}"
            class="bg-white rounded-xl lg:rounded-2xl border border-gray-100 p-4 lg:p-6 card-hover block">
            <div
                class="w-10 h-10 lg:w-12 lg:h-12 bg-gray-100 rounded-lg lg:rounded-xl flex items-center justify-center mb-3 lg:mb-4">
                <svg class="w-5 h-5 lg:w-6 lg:h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
            </div>
            <h3 class="font-semibold text-gray-900 text-sm lg:text-base mb-1">Tambah Barang</h3>
            <p class="text-xs lg:text-sm text-gray-500">Tambahkan barang baru</p>
        </a>

        <a href="{{ route('admin.users.create') }}"
            class="bg-white rounded-xl lg:rounded-2xl border border-gray-100 p-4 lg:p-6 card-hover block">
            <div
                class="w-10 h-10 lg:w-12 lg:h-12 bg-gray-100 rounded-lg lg:rounded-xl flex items-center justify-center mb-3 lg:mb-4">
                <svg class="w-5 h-5 lg:w-6 lg:h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
            </div>
            <h3 class="font-semibold text-gray-900 text-sm lg:text-base mb-1">Tambah User</h3>
            <p class="text-xs lg:text-sm text-gray-500">Daftarkan user baru</p>
        </a>

        <a href="#"
            class="bg-white rounded-xl lg:rounded-2xl border border-gray-100 p-4 lg:p-6 card-hover block sm:col-span-2 lg:col-span-1">
            <div
                class="w-10 h-10 lg:w-12 lg:h-12 bg-gray-100 rounded-lg lg:rounded-xl flex items-center justify-center mb-3 lg:mb-4">
                <svg class="w-5 h-5 lg:w-6 lg:h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <h3 class="font-semibold text-gray-900 text-sm lg:text-base mb-1">Lihat Laporan</h3>
            <p class="text-xs lg:text-sm text-gray-500">Generate laporan</p>
        </a>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-xl lg:rounded-2xl border border-gray-100 p-4 lg:p-6">
        <h2 class="font-semibold text-gray-900 text-sm lg:text-base mb-4">Aktivitas Terbaru</h2>
        <div class="space-y-4">
            @forelse($recentActivities as $activity)
                <div class="flex items-start gap-4 p-4 rounded-xl bg-gray-50 hover:bg-gray-100 transition">
                    <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center shrink-0 font-bold">
                        {{ substr($activity->user->nama_lengkap ?? $activity->user->email ?? 'S', 0, 1) }}
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">{{ $activity->description }}</p>
                        <p class="text-xs text-gray-500 mt-1">
                            Oleh: <span class="font-medium text-gray-700">{{ $activity->user->nama_lengkap ?? $activity->user->email ?? 'System' }}</span>
                            &bull; {{ $activity->created_at->diffForHumans() }}
                        </p>
                    </div>
                    <span class="px-2 py-1 text-[10px] font-medium uppercase tracking-wide rounded-full 
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
    <div class="bg-white rounded-xl lg:rounded-2xl border border-gray-100 p-4 lg:p-6 mt-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="font-semibold text-gray-900 text-sm lg:text-base">Peminjaman Terbaru</h2>
            <a href="{{ route('admin.peminjaman.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">Lihat Semua</a>
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
                                <span class="block text-gray-900">{{ $pinjam->barang->nama_barang }}</span>
                                <span class="text-xs text-gray-500">{{ $pinjam->jumlah }} Unit</span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs font-medium rounded-full
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
@endsection