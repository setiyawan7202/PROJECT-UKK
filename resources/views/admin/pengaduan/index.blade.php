@extends('layouts.admin')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Daftar Pengaduan</h1>
    </div>

    <!-- Filters -->
    <div class="bg-white p-4 rounded-xl border border-gray-100 mb-6 shadow-sm">
        <form action="{{ route('admin.pengaduan.index') }}" method="GET" class="flex gap-4">
            <select name="status" class="rounded-lg border-gray-300 text-sm focus:ring-black focus:border-black">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="processed" {{ request('status') == 'processed' ? 'selected' : '' }}>Diproses</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
            </select>
            <button type="submit"
                class="px-4 py-2 bg-gray-900 text-white rounded-lg text-sm font-medium hover:bg-black transition">
                Filter
            </button>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 font-medium text-gray-500">Pelapor</th>
                        <th class="px-6 py-4 font-medium text-gray-500">Judul</th>
                        <th class="px-6 py-4 font-medium text-gray-500">Lokasi</th>
                        <th class="px-6 py-4 font-medium text-gray-500">Tanggal</th>
                        <th class="px-6 py-4 font-medium text-gray-500">Status</th>
                        <th class="px-6 py-4 font-medium text-gray-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($pengaduan as $item)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ $item->user->nama_lengkap ?? $item->user->email }}
                                </div>
                                <div class="text-xs text-gray-500">{{ $item->user->role }}</div>
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $item->judul }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $item->lokasi }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $item->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-4">
                                @if($item->status == 'pending')
                                    <span
                                        class="px-2.5 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-medium">Pending</span>
                                @elseif($item->status == 'processed')
                                    <span
                                        class="px-2.5 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-medium">Diproses</span>
                                @elseif($item->status == 'completed')
                                    <span
                                        class="px-2.5 py-1 rounded-full bg-green-100 text-green-700 text-xs font-medium">Selesai</span>
                                @else
                                    <span
                                        class="px-2.5 py-1 rounded-full bg-red-100 text-red-700 text-xs font-medium">Ditolak</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.pengaduan.show', $item->id) }}"
                                    class="text-blue-600 hover:text-blue-800 font-medium">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                Belum ada pengaduan data.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pengaduan->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $pengaduan->links() }}
            </div>
        @endif
    </div>
@endsection