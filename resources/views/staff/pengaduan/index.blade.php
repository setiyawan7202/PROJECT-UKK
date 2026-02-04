@extends('layouts.staff')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Daftar Pengaduan</h1>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-gray-50 border border-gray-200 text-gray-800 rounded-xl text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-4 bg-white border border-gray-200 text-red-600 rounded-xl text-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white p-4 rounded-xl border border-gray-100 mb-6 shadow-sm">
        <form action="{{ route('staff.pengaduan.index') }}" method="GET" class="flex gap-4">
            <select name="status" class="rounded-lg border-gray-300 text-sm focus:ring-black focus:border-black">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="processed" {{ request('status') == 'processed' ? 'selected' : '' }}>Diproses</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
            </select>
            <button type="submit"
                class="px-4 py-2 bg-black text-white rounded-lg text-sm font-medium hover:bg-gray-800 transition">
                Filter
            </button>
        </form>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 font-medium text-gray-500">Kode</th>
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
                                            <span
                                                class="font-mono text-xs font-bold bg-gray-100 text-gray-700 px-2 py-1 rounded border border-gray-200">{{ $item->kode ?? '-' }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-gray-900">{{ $item->user->nama_lengkap ?? $item->user->email }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ $item->user->siswa->kelas->nama_kelas ?? ucfirst($item->user->role) }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 font-medium text-gray-900">{{ $item->judul }}</td>
                                        <td class="px-6 py-4 text-gray-600">{{ $item->lokasi }}</td>
                                        <td class="px-6 py-4 text-gray-600">{{ $item->created_at->format('d M Y') }}</td>
                                        <td class="px-6 py-4">
                                            @php
                                                $statusColors = [
                                                    'pending' => 'bg-gray-100 text-gray-600 border border-gray-200',
                                                    'processed' => 'bg-gray-800 text-white border border-gray-800',
                                                    'completed' => 'bg-white text-gray-800 border border-gray-300',
                                                    'rejected' => 'bg-white text-gray-500 border border-gray-200',
                                                ];
                                                $statusLabels = [
                                                    'pending' => 'Pending',
                                                    'processed' => 'Diproses',
                                                    'completed' => 'Selesai',
                                                    'rejected' => 'Ditolak',
                                                ];
                                            @endphp
                         <span
                                                class="px-2.5 py-1 rounded-md text-xs font-bold {{ $statusColors[$item->status] ?? 'bg-gray-100' }}">
                                                {{ $statusLabels[$item->status] ?? ucfirst($item->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <a href="{{ route('staff.pengaduan.show', $item->id) }}"
                                                class="text-gray-700 hover:text-black font-medium underline">Detail</a>
                                        </td>
                                    </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
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