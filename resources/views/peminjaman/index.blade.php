@extends('layouts.main')

@section('title', 'Riwayat Peminjaman')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Riwayat Peminjaman</h1>
        <p class="text-gray-500">Daftar peminjaman barang yang Anda ajukan</p>
    </div>

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
                        <th class="px-6 py-4 font-semibold">Barang</th>
                        <th class="px-6 py-4 font-semibold">Tgl Pinjam</th>
                        <th class="px-6 py-4 font-semibold">Tgl Kembali (Rencana)</th>
                        <th class="px-6 py-4 font-semibold">Status</th>
                        <th class="px-6 py-4 font-semibold">Kode Unit</th>
                        <th class="px-6 py-4 font-semibold">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($peminjaman as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ $item->barang->nama_barang }}
                                <div class="text-xs text-gray-500">{{ $item->barang->kategori->nama_kategori ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4">{{ $item->tgl_pinjam->format('d M Y') }}</td>
                            <td class="px-6 py-4">{{ $item->tgl_kembali_rencana->format('d M Y') }}</td>
                            <td class="px-6 py-4">
                                @if($item->status == 'pending')
                                    <span
                                        class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs font-semibold">Menunggu</span>
                                @elseif($item->status == 'approved')
                                    <span
                                        class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-semibold">Disetujui</span>
                                @elseif($item->status == 'active')
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-semibold">Sedang
                                        Dipinjam</span>
                                @elseif($item->status == 'completed')
                                    <span
                                        class="bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-xs font-semibold">Selesai</span>
                                @elseif($item->status == 'rejected')
                                    <span
                                        class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs font-semibold">Ditolak</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-mono text-xs">
                                {{ $item->barangUnit->kode_unit ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-gray-500 text-xs">
                                @if($item->status == 'rejected')
                                    <span class="text-red-600">Alasan: {{ $item->keterangan_penolakan }}</span>
                                @else
                                    {{ Str::limit($item->tujuan_pinjam, 30) }}
                                @endif
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