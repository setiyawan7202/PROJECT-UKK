@extends('layouts.main')

@section('title', 'Pengaduan Saya - SIAPRAS')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Pengaduan Saya</h1>
        <a href="{{ route('pengaduan.create') }}" class="px-4 py-2 bg-black text-white rounded-lg text-sm font-medium hover:bg-gray-800 transition">
            + Buat Pengaduan
        </a>
    </div>

    @if($pengaduan->isEmpty())
        <div class="bg-white rounded-xl border border-gray-100 p-8 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada pengaduan</h3>
            <p class="text-gray-500 mb-6">Jika Anda menemukan kerusakan sarpras, laporkan di sini.</p>
            <a href="{{ route('pengaduan.create') }}" class="text-black font-medium hover:underline">Buat Pengaduan Baru &rarr;</a>
        </div>
    @else
        <div class="bg-white rounded-xl border border-gray-100 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 font-medium text-gray-500">Judul</th>
                            <th class="px-6 py-4 font-medium text-gray-500">Lokasi</th>
                            <th class="px-6 py-4 font-medium text-gray-500">Tanggal</th>
                            <th class="px-6 py-4 font-medium text-gray-500">Status</th>
                            <th class="px-6 py-4 font-medium text-gray-500">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($pengaduan as $item)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $item->judul }}</td>
                                <td class="px-6 py-4 text-gray-600">
                                    {{ $item->ruangan->nama_ruangan ?? $item->lokasi ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-gray-600">{{ $item->created_at->format('d M Y') }}</td>
                                <td class="px-6 py-4">
                                    @if($item->status == 'pending')
                                        <span class="px-2.5 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-medium">Menunggu</span>
                                    @elseif($item->status == 'processed')
                                        <span class="px-2.5 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-medium">Diproses</span>
                                    @elseif($item->status == 'completed')
                                        <span class="px-2.5 py-1 rounded-full bg-green-100 text-green-700 text-xs font-medium">Selesai</span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full bg-red-100 text-red-700 text-xs font-medium">Ditolak</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('pengaduan.show', $item->id) }}" class="text-blue-600 hover:text-blue-800 font-medium">Detail</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if($pengaduan->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $pengaduan->links() }}
                </div>
            @endif
        </div>
    @endif
@endsection
