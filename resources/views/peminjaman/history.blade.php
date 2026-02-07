@extends('layouts.main')

@section('title', 'Riwayat Peminjaman')

@section('content')
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Riwayat Peminjaman</h1>
            <p class="text-gray-500">Daftar riwayat peminjaman barang yang sudah selesai atau dibatalkan</p>
        </div>
        <div class="flex bg-gray-100 p-1 rounded-lg">
            <a href="{{ route('peminjaman.index') }}"
                class="px-4 py-2 rounded-md text-gray-500 hover:text-black font-medium text-sm transition">
                Peminjaman Aktif
            </a>
            <a href="{{ route('riwayat.index') }}"
                class="px-4 py-2 rounded-md bg-white text-black shadow-sm font-medium text-sm transition">
                Riwayat
            </a>
        </div>
    </div>

    @if(now()->isWeekend())
        <div class="mb-6 p-4 bg-orange-50 border border-orange-200 text-orange-800 rounded-xl flex items-center gap-3">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <div>
                <span class="font-bold">Pelayanan Tutup</span>
                <p class="text-sm mt-1">Hari ini adalah hari libur (Sabtu/Minggu). Layanan peminjaman dan pengembalian barang
                    tutup.</p>
            </div>
        </div>
    @endif

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 border-b border-gray-100">
                        <th class="px-6 py-4 font-semibold">Kode Pinjam</th>
                        <th class="px-6 py-4 font-semibold">Barang</th>
                        <th class="px-6 py-4 font-semibold">Tgl Pinjam</th>
                        <th class="px-6 py-4 font-semibold">Tgl Kembali</th>
                        <th class="px-6 py-4 font-semibold">Status</th>
                        <th class="px-6 py-4 font-semibold">Kode Unit</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($peminjaman as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <span class="font-mono text-xs font-medium bg-gray-100 text-gray-700 px-2 py-1 rounded">
                                    {{ $item->kode ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ $item->barang->nama_barang }}
                                <div class="text-xs text-gray-500">{{ $item->barang->kategori->nama_kategori ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4">{{ $item->tgl_pinjam->format('d M Y') }}</td>
                            <td class="px-6 py-4">{{ $item->tgl_kembali_rencana->format('d M Y') }}</td>
                            <td class="px-6 py-4">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-gray-100 text-gray-600 border border-gray-200',
                                        'approved' => 'bg-black text-white border border-black',
                                        'active' => 'bg-gray-800 text-white border border-gray-800',
                                        'completed' => 'bg-white text-gray-800 border border-gray-300',
                                        'rejected' => 'bg-white text-red-600 border border-red-200',
                                        'canceled' => 'bg-gray-50 text-gray-500 border border-gray-200',
                                    ];
                                    $statusLabels = [
                                        'pending' => 'Menunggu',
                                        'approved' => 'Disetujui',
                                        'active' => 'Dipinjam',
                                        'completed' => 'Selesai',
                                        'rejected' => 'Ditolak',
                                        'canceled' => 'Dibatalkan',
                                    ];
                                @endphp
                                <span
                                    class="px-2.5 py-1 rounded-md text-xs font-bold {{ $statusColors[$item->status] ?? 'bg-gray-100' }}">
                                    {{ $statusLabels[$item->status] ?? ucfirst($item->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-mono text-xs font-medium bg-gray-100 text-gray-700 px-2 py-1 rounded">
                                    {{ $item->barangUnit->kode_unit ?? '-' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                <p>Belum ada riwayat peminjaman.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100">
            {{ $peminjaman->links() }}
        </div>
    </div>
    </div>
@endsection