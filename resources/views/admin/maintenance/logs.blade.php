@extends('layouts.admin')

@section('title', 'Riwayat Maintenance')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-xl lg:text-2xl font-bold text-gray-900">Riwayat Maintenance</h1>
            <p class="text-sm text-gray-500">Log aktivitas maintenance yang telah dilakukan</p>
        </div>
        <a href="{{ route('admin.maintenance.logs.create') }}"
            class="inline-flex items-center px-4 py-2 bg-black text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Catat Aktivitas
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 border-b border-gray-100">
                        <th class="px-4 py-3 font-medium">Tanggal</th>
                        <th class="px-4 py-3 font-medium">Unit</th>
                        <th class="px-4 py-3 font-medium">Deskripsi</th>
                        <th class="px-4 py-3 font-medium">Teknisi</th>
                        <th class="px-4 py-3 font-medium">Dicatat Oleh</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 font-medium">
                                {{ $log->maintenance_date->format('d M Y') }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="block text-gray-900">{{ $log->barangUnit->barang->nama_barang ?? '-' }}</span>
                                <span class="text-xs text-gray-500">{{ $log->barangUnit->kode_unit ?? '-' }}</span>
                            </td>
                            <td class="px-4 py-3 text-gray-600 max-w-xs truncate">
                                {{ $log->description }}
                            </td>
                            <td class="px-4 py-3 text-gray-600">
                                {{ $log->technician_name }}
                            </td>
                            <td class="px-4 py-3 text-gray-500">
                                {{ $log->performer->nama_lengkap ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                Belum ada riwayat maintenance.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $logs->links() }}
    </div>
@endsection