@extends('layouts.admin')

@section('title', 'Jadwal Maintenance')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-xl lg:text-2xl font-bold text-gray-900">Jadwal Maintenance</h1>
            <p class="text-sm text-gray-500">Kelola jadwal maintenance preventif per kategori/barang</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.maintenance.logs') }}"
                class="inline-flex items-center px-4 py-2 border border-gray-200 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                Riwayat
            </a>
            <a href="{{ route('admin.maintenance.create') }}"
                class="inline-flex items-center px-4 py-2 bg-black text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Buat Jadwal
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Upcoming Maintenance Alert -->
    @if($upcoming->count() > 0)
        <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-xl">
            <h3 class="font-semibold text-yellow-800 mb-2">⚠️ Maintenance Mendekati</h3>
            <div class="space-y-2">
                @foreach($upcoming as $schedule)
                    <div class="text-sm text-yellow-700">
                        <span
                            class="font-medium">{{ $schedule->kategori->nama ?? $schedule->barang->nama_barang ?? 'Unknown' }}</span>
                        - Jatuh tempo: {{ $schedule->next_maintenance_at->format('d M Y') }}
                        @if($schedule->isOverdue())
                            <span class="text-red-600 font-bold">(OVERDUE)</span>
                        @else
                            <span>({{ $schedule->days_until }} hari lagi)</span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Schedules Table -->
    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 border-b border-gray-100">
                        <th class="px-4 py-3 font-medium">Target</th>
                        <th class="px-4 py-3 font-medium">Interval</th>
                        <th class="px-4 py-3 font-medium">Maintenance Berikutnya</th>
                        <th class="px-4 py-3 font-medium">Status</th>
                        <th class="px-4 py-3 font-medium text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($schedules as $schedule)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3">
                                @if($schedule->kategori_id)
                                    <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded-full">Kategori</span>
                                    <span class="ml-2 font-medium">{{ $schedule->kategori->nama ?? '-' }}</span>
                                @else
                                    <span class="px-2 py-1 bg-purple-100 text-purple-700 text-xs rounded-full">Barang</span>
                                    <span class="ml-2 font-medium">{{ $schedule->barang->nama_barang ?? '-' }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-600">
                                {{ $schedule->interval_days }} hari
                            </td>
                            <td class="px-4 py-3">
                                {{ $schedule->next_maintenance_at ? $schedule->next_maintenance_at->format('d M Y') : '-' }}
                            </td>
                            <td class="px-4 py-3">
                                @if($schedule->isOverdue())
                                    <span class="px-2 py-1 bg-red-100 text-red-700 text-xs rounded-full">Overdue</span>
                                @elseif($schedule->days_until !== null && $schedule->days_until <= 7)
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-xs rounded-full">Segera</span>
                                @else
                                    <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full">OK</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.maintenance.edit', $schedule) }}"
                                        class="text-blue-600 hover:text-blue-800">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.maintenance.destroy', $schedule) }}" method="POST"
                                        onsubmit="return confirm('Hapus jadwal ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                Belum ada jadwal maintenance.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $schedules->links() }}
    </div>
@endsection