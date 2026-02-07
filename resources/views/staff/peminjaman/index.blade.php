@extends('layouts.staff')

@section('title', 'Manajemen Peminjaman')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Manajemen Peminjaman</h1>
        <p class="text-sm text-gray-500">Kelola persetujuan dan status peminjaman.</p>
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

    <!-- Filters -->
    <div class="bg-white p-4 rounded-xl border border-gray-100 mb-6">
        <form method="GET" action="{{ route('staff.peminjaman.index') }}" class="flex gap-4">
            <select name="status" class="px-4 py-2 border rounded-lg text-sm focus:ring-black focus:border-black"
                onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </form>
    </div>

    <div class="bg-white border border-gray-100 rounded-xl overflow-hidden">
        <div class="overflow-x-auto w-full">
            <table class="w-full min-w-[1000px] text-left text-sm whitespace-nowrap">
                <thead class="bg-gray-50 text-gray-500">
                    <tr>
                        <th class="px-6 py-3 font-semibold">Kode</th>
                        <th class="px-6 py-3 font-semibold">Peminjam</th>
                        <th class="px-6 py-3 font-semibold">Barang</th>
                        <th class="px-6 py-3 font-semibold">Tgl Pinjam</th>
                        <th class="px-6 py-3 font-semibold">Jumlah</th>
                        <th class="px-6 py-3 font-semibold">Tujuan</th>
                        <th class="px-6 py-3 font-semibold">Status</th>
                        <th class="px-6 py-3 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($peminjaman as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <span class="font-mono text-xs font-bold text-gray-700 bg-gray-100 px-2 py-1 rounded">
                                    {{ $item->kode ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ $item->user->nama_lengkap }}</div>
                                <div class="text-xs text-gray-500">
                                    {{ $item->user->siswa->kelas->nama_kelas ?? ucfirst($item->user->role) }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium">{{ $item->barang->nama_barang }}</div>
                                <div class="text-xs text-gray-500 mt-1">
                                    Unit:
                                    <span class="font-mono font-medium bg-gray-100 text-gray-700 px-1.5 py-0.5 rounded">
                                        {{ $item->barangUnit->kode_unit ?? '-' }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div>{{ $item->tgl_pinjam->format('d/m/Y') }}</div>
                                <div class="text-xs text-gray-400">s/d {{ $item->tgl_kembali_rencana->format('d/m/Y') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-bold text-gray-900">{{ $item->jumlah }}</span> Unit
                            </td>
                            <td class="px-6 py-4 text-gray-500 max-w-xs truncate" title="{{ $item->tujuan_pinjam }}">
                                {{ Str::limit($item->tujuan_pinjam, 30) }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $colors = [
                                        'pending' => 'bg-gray-100 text-gray-600 border border-gray-200',
                                        'approved' => 'bg-black text-white border border-black',
                                        'active' => 'bg-gray-800 text-white border border-gray-800',
                                        'completed' => 'bg-white text-gray-800 border border-gray-300',
                                        'rejected' => 'bg-white text-red-600 border border-red-200',
                                        'overdue' => 'bg-red-50 text-red-700 border border-red-200',
                                    ];
                                @endphp
                                <span
                                    class="px-2 py-1 rounded-md text-xs font-semibold {{ $colors[$item->status] ?? 'bg-gray-100' }}">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if($item->status == 'pending')
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('staff.peminjaman.show', $item->id) }}"
                                            class="bg-white border border-gray-200 text-gray-700 px-3 py-1 rounded-lg text-xs hover:bg-gray-50 font-medium">
                                            Detail & Aksi
                                        </a>
                                    </div>
                                @elseif($item->status == 'approved')
                                    <!-- Approved Actions -->
                                    <div class="flex justify-end gap-2">
                                        <form action="{{ route('staff.peminjaman.activate', $item->id) }}" method="POST"
                                            onsubmit="return confirm('Konfirmasi pengambilan barang?')">
                                            @csrf
                                            <button type="submit"
                                                class="bg-black text-white px-3 py-1 rounded-lg text-xs hover:bg-gray-800">
                                                Ambil Barang
                                            </button>
                                        </form>
                                        <a href="{{ route('staff.peminjaman.show', $item->id) }}"
                                            class="bg-white border border-gray-200 text-gray-700 px-3 py-1 rounded-lg text-xs hover:bg-gray-50 font-medium">
                                            Detail
                                        </a>
                                        <a href="{{ route('staff.peminjaman.bukti', $item->id) }}" target="_blank"
                                            class="bg-white border border-gray-200 text-gray-700 px-3 py-1 rounded-lg text-xs hover:bg-gray-50">
                                            Cetak Bukti
                                        </a>
                                    </div>
                                @elseif($item->status == 'active')
                                    <!-- Active Actions -->
                                    <a href="{{ route('staff.peminjaman.return', $item->id) }}"
                                        class="bg-black text-white px-3 py-1 rounded-lg text-xs hover:bg-gray-800">
                                        Kembalikan
                                    </a>
                                    <a href="{{ route('staff.peminjaman.show', $item->id) }}"
                                        class="bg-white border border-gray-200 text-gray-700 px-3 py-1 rounded-lg text-xs hover:bg-gray-50 font-medium">
                                        Detail
                                    </a>
                                    <a href="{{ route('staff.peminjaman.bukti', $item->id) }}" target="_blank"
                                        class="bg-white border border-gray-200 text-gray-700 px-3 py-1 rounded-lg text-xs hover:bg-gray-50">
                                        Cetak Bukti
                                    </a>
                                @else
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('staff.peminjaman.show', $item->id) }}"
                                            class="bg-white border border-gray-200 text-gray-700 px-3 py-1 rounded-lg text-xs hover:bg-gray-50 font-medium">
                                            Detail
                                        </a>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-gray-500">Tidak ada data peminjaman.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100">
            {{ $peminjaman->links() }}
        </div>
    </div>

    <!-- Modals Removed (Moved to Detail Page) -->
@endsection