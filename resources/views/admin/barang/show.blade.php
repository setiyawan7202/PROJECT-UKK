@extends('layouts.admin')

@section('title', 'Detail Barang - ' . $barang->nama_barang)

@section('content')
    <div class="mb-6 lg:mb-8">
        <a href="{{ route('admin.barang.index') }}"
            class="inline-flex items-center gap-2 text-gray-500 hover:text-gray-900 text-sm mb-4 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali
        </a>
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl lg:text-2xl font-bold text-gray-900">{{ $barang->nama_barang }}</h1>
                <p class="text-sm text-gray-500">Detail unit barang</p>
            </div>
            <a href="{{ route('admin.barang.edit', $barang->id) }}"
                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gray-100 text-gray-700 rounded-xl font-medium text-sm hover:bg-gray-200 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit Barang
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-4 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <!-- Info Card -->
    <div class="bg-white border border-gray-100 rounded-xl p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div>
                <p class="text-xs uppercase text-gray-400 font-semibold mb-1">Kode</p>
                <p class="font-mono font-medium text-gray-900">{{ $barang->kode }}</p>
            </div>
            <div>
                <p class="text-xs uppercase text-gray-400 font-semibold mb-1">Kategori</p>
                <p class="font-medium text-gray-900">{{ $barang->kategori->nama_kategori ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs uppercase text-gray-400 font-semibold mb-1">Ruangan</p>
                <p class="font-medium text-gray-900">{{ $barang->ruangan->nama_ruangan ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs uppercase text-gray-400 font-semibold mb-1">Tipe</p>
                <div class="flex flex-col gap-1">
                    <p class="font-medium {{ $barang->tipe_barang == 'maintenance' ? 'text-blue-600' : 'text-orange-600' }}">
                        {{ $barang->tipe_barang == 'maintenance' ? 'Bisa Diperbaiki' : 'Sekali Pakai' }}
                    </p>
                    <span class="text-xs px-2 py-0.5 rounded-full w-fit {{ $barang->jenis_aset == 'tik' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600' }}">
                        {{ $barang->jenis_aset == 'tik' ? 'Aset TIK' : 'Non-TIK' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Units Table -->
    <div class="bg-white border border-gray-100 rounded-xl overflow-hidden">
        <div class="p-4 border-b border-gray-100">
            <h2 class="font-semibold text-gray-900">Daftar Unit ({{ $barang->units->count() }} unit)</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 border-b border-gray-100">
                        <th class="px-6 py-3 font-semibold text-xs uppercase">No</th>
                        <th class="px-6 py-3 font-semibold text-xs uppercase">Kode Unit</th>
                        <th class="px-6 py-3 font-semibold text-xs uppercase">Kondisi</th>
                        <th class="px-6 py-3 font-semibold text-xs uppercase">Status</th>
                        <th class="px-6 py-3 font-semibold text-xs uppercase">Tanggal Dibuat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($barang->units as $index => $unit)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-gray-500">{{ $index + 1 }}</td>
                            <td class="px-6 py-4">
                                <span class="font-mono bg-gray-100 text-gray-800 px-2 py-1 rounded text-sm">{{ $unit->kode_unit }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <form action="{{ route('admin.barang.updateUnit', $unit->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="field" value="kondisi">
                                    <select name="kondisi" onchange="this.form.submit()"
                                        class="px-3 py-1.5 text-sm border border-gray-200 rounded-lg bg-white focus:outline-none cursor-pointer
                                        {{ $unit->kondisi == 'Baik' ? 'text-green-600' : ($unit->kondisi == 'Rusak Ringan' ? 'text-yellow-600' : 'text-red-600') }}">
                                        <option value="Baik" {{ $unit->kondisi == 'Baik' ? 'selected' : '' }}>Baik</option>
                                        <option value="Rusak Ringan" {{ $unit->kondisi == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                                        <option value="Rusak Berat" {{ $unit->kondisi == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                                    </select>
                                </form>
                            </td>
                            <td class="px-6 py-4">
                                @if($barang->tipe_barang == 'maintenance')
                                    <form action="{{ route('admin.barang.updateUnit', $unit->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="field" value="status">
                                        <select name="status" onchange="this.form.submit()"
                                            class="px-3 py-1.5 text-sm border border-gray-200 rounded-lg bg-white focus:outline-none cursor-pointer
                                            {{ $unit->status == 'aktif' ? 'text-green-600' : ($unit->status == 'maintenance' ? 'text-blue-600' : 'text-red-600') }}">
                                            <option value="aktif" {{ $unit->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                            <option value="maintenance" {{ $unit->status == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                            <option value="rusak" {{ $unit->status == 'rusak' ? 'selected' : '' }}>Rusak</option>
                                        </select>
                                    </form>
                                @else
                                    <span class="px-3 py-1.5 text-sm {{ $unit->status == 'aktif' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $unit->status == 'aktif' ? 'Aktif' : 'Dibuang' }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-500">{{ $unit->created_at->format('d M Y, H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <p>Belum ada unit</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection