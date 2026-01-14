@extends('layouts.admin')

@section('title', 'Manajemen Ruangan')

@section('content')
    <div class="mb-6 lg:mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl lg:text-2xl font-bold text-gray-900">Manajemen Ruangan</h1>
            <p class="text-sm text-gray-500">Kelola data ruangan</p>
        </div>
        <a href="{{ route('admin.ruangan.create') }}"
            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-black text-white rounded-xl font-medium text-sm hover:bg-gray-800 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Tambah Ruangan
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-4 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white border border-gray-100 rounded-xl overflow-hidden">
        <div class="p-4 border-b border-gray-100">
            <form action="{{ route('admin.ruangan.index') }}" method="GET" class="flex gap-2">
                <div class="relative flex-1">
                    <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari ruangan..."
                        class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-1 focus:ring-black">
                </div>
                <button type="submit"
                    class="bg-gray-100 px-4 py-2 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-200 transition">Cari</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 border-b border-gray-100">
                        <th class="px-6 py-3 font-semibold text-xs uppercase">Kode</th>
                        <th class="px-6 py-3 font-semibold text-xs uppercase">Nama Ruangan</th>
                        <th class="px-6 py-3 font-semibold text-xs uppercase">Lokasi</th>
                        <th class="px-6 py-3 font-semibold text-xs uppercase">Keterangan</th>
                        <th class="px-6 py-3 font-semibold text-xs uppercase text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($ruangans as $ruangan)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $ruangan->kode_ruangan }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $ruangan->nama_ruangan }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $ruangan->lokasi }}</td>
                            <td class="px-6 py-4 text-gray-500 truncate max-w-xs">{{ $ruangan->keterangan ?? '-' }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.ruangan.edit', $ruangan->id) }}"
                                        class="p-2 text-gray-500 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.ruangan.destroy', $ruangan->id) }}" method="POST"
                                        onsubmit="return confirm('Hapus ruangan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                <p>Belum ada data ruangan</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection