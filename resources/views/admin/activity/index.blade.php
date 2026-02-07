@extends('layouts.admin')

@section('title', 'Activity Log')

@section('content')

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-xl lg:text-2xl font-bold text-gray-900">Activity Log</h1>
            <p class="text-sm text-gray-500">Riwayat aktivitas sistem</p>
        </div>
        <a href="{{ route('admin.activity.exportPdf', request()->query()) }}"
            class="inline-flex items-center gap-2 px-4 py-2 bg-black text-white rounded-lg hover:bg-gray-800 transition text-sm font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Export PDF
        </a>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl border border-gray-100 p-4 mb-6">
        <form method="GET" action="{{ route('admin.activity.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Tipe Aksi</label>
                <select name="action"
                    class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-gray-200 focus:border-gray-300">
                    <option value="">Semua Aksi</option>
                    @foreach ($actionTypes as $type)
                        <option value="{{ $type }}" {{ request('action') == $type ? 'selected' : '' }}>
                            {{ $type }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">User</label>
                <select name="user_id"
                    class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-gray-200 focus:border-gray-300">
                    <option value="">Semua User</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->nama_lengkap ?? $user->email }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}"
                    class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-gray-200 focus:border-gray-300">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}"
                    class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-gray-200 focus:border-gray-300">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit"
                    class="flex-1 px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition text-sm font-medium">
                    Filter
                </button>
                <a href="{{ route('admin.activity.index') }}"
                    class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm font-medium">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Activity Table --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-500 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-3 font-medium text-xs uppercase">Waktu</th>
                        <th class="px-6 py-3 font-medium text-xs uppercase">User</th>
                        <th class="px-6 py-3 font-medium text-xs uppercase">Aksi</th>
                        <th class="px-6 py-3 font-medium text-xs uppercase">Deskripsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($activities as $activity)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-gray-900">{{ $activity->created_at->format('d M Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $activity->created_at->format('H:i:s') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-8 h-8 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center font-bold text-sm">
                                        {{ substr($activity->user->nama_lengkap ?? $activity->user->email ?? 'U', 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">
                                            {{ $activity->user->nama_lengkap ?? $activity->user->email ?? '-' }}
                                        </div>
                                        <div class="text-xs text-gray-500">{{ $activity->user->role ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-700">
                                    {{ $activity->action }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-gray-900">{{ $activity->description }}</p>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-sm">Tidak ada data aktivitas</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($activities->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $activities->links() }}
            </div>
        @endif
    </div>
@endsection