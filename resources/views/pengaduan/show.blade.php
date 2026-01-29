@extends('layouts.main')

@section('title', 'Detail Pengaduan - SIAPRAS')

@section('content')
    <div class="max-w-3xl mx-auto">
        <a href="{{ route('pengaduan.index') }}"
            class="inline-flex items-center gap-2 text-gray-500 hover:text-gray-900 mb-6 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Daftar
        </a>

        <div class="bg-white rounded-xl border border-gray-100 overflow-hidden shadow-sm mb-6">
            <div class="p-6 sm:p-8 border-b border-gray-100">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $pengaduan->judul }}</h1>
                        <div class="flex items-center gap-4 text-sm text-gray-500">
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                {{ $pengaduan->ruangan->nama_ruangan ?? $pengaduan->lokasi ?? '-' }}
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ $pengaduan->created_at->format('d M Y, H:i') }}
                            </span>
                        </div>
                    </div>
                    <div>
                        @if($pengaduan->status == 'pending')
                            <span
                                class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-sm font-medium">Menunggu</span>
                        @elseif($pengaduan->status == 'processed')
                            <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-sm font-medium">Diproses</span>
                        @elseif($pengaduan->status == 'completed')
                            <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm font-medium">Selesai</span>
                        @else
                            <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-sm font-medium">Ditolak</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="p-6 sm:p-8 space-y-6">
                <!-- Deskripsi -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Deskripsi Masalah</h3>
                    <p class="text-gray-900 leading-relaxed whitespace-pre-line">{{ $pengaduan->deskripsi }}</p>
                </div>

                <!-- Ruangan Detail -->
                @if($pengaduan->ruangan)
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="font-semibold text-gray-900 mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Lokasi Ruangan
                        </h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="block text-gray-500 text-xs">Nama Ruangan</span>
                                <span class="font-medium text-gray-900">{{ $pengaduan->ruangan->nama_ruangan }}</span>
                            </div>
                            @if($pengaduan->ruangan->lokasi)
                                <div>
                                    <span class="block text-gray-500 text-xs">Lokasi</span>
                                    <span class="font-medium text-gray-900">{{ $pengaduan->ruangan->lokasi }}</span>
                                </div>
                            @endif
                            @if(!$pengaduan->barang)
                                <div class="col-span-2">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Pengaduan Ruangan (tidak ada barang spesifik)
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Barang Terdampak --}}
                @if($pengaduan->barang)
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="font-semibold text-gray-900 mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            Barang Terdampak
                        </h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="block text-gray-500 text-xs">Nama Barang</span>
                                <span class="font-medium text-gray-900">{{ $pengaduan->barang->nama_barang }}</span>
                            </div>
                            <div>
                                <span class="block text-gray-500 text-xs">Kategori</span>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                    {{ $pengaduan->barang->kategori->nama_kategori ?? '-' }}
                                </span>
                            </div>
                            @if($pengaduan->barangUnit)
                                <div>
                                    <span class="block text-gray-500 text-xs">Kode Unit</span>
                                    <span
                                        class="font-mono bg-gray-200 px-2 py-0.5 rounded text-xs">{{ $pengaduan->barangUnit->kode_unit }}</span>
                                </div>
                                <div>
                                    <span class="block text-gray-500 text-xs">Kondisi Unit</span>
                                    <span
                                        class="font-medium text-gray-900">{{ ucwords(str_replace('_', ' ', $pengaduan->barangUnit->kondisi ?? '-')) }}</span>
                                </div>
                            @endif
                            @if($pengaduan->kondisi)
                                <div class="col-span-2">
                                    <span class="block text-gray-500 text-xs mb-1">Kondisi Dilaporkan</span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                                            @if($pengaduan->kondisi == 'baik') bg-green-100 text-green-700
                                                                            @elseif($pengaduan->kondisi == 'rusak_ringan') bg-yellow-100 text-yellow-700
                                                                            @elseif($pengaduan->kondisi == 'rusak_berat') bg-orange-100 text-orange-700
                                                                            @else bg-red-100 text-red-700
                                                                            @endif">
                                        {{ ucwords(str_replace('_', ' ', $pengaduan->kondisi)) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                @elseif($pengaduan->jenis_sarpras)
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="font-semibold text-gray-900 mb-2">Jenis Sarpras</h3>
                        <p class="text-gray-700">{{ $pengaduan->jenis_sarpras }}</p>
                    </div>
                @endif

                <!-- Foto -->
                @if($pengaduan->foto)
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Foto Bukti</h3>
                        <div class="rounded-lg overflow-hidden border border-gray-200 inline-block">
                            <img src="{{ Str::startsWith($pengaduan->foto, 'http') ? $pengaduan->foto : Storage::url($pengaduan->foto) }}"
                                alt="Bukti Foto" class="max-w-full h-auto max-h-96 object-contain">
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Tanggapan / Catatan -->
        <div class="bg-white rounded-xl border border-gray-100 p-6 sm:p-8 shadow-sm">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Riwayat Tanggapan</h3>

            <div class="space-y-4 mb-6">
                @forelse($pengaduan->catatan as $catatan)
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                        <div class="flex justify-between items-start mb-2">
                            <span class="font-semibold text-gray-900">{{ $catatan->user->nama_lengkap ?? 'Admin' }}</span>
                            <span class="text-xs text-gray-500">{{ $catatan->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        <p class="text-gray-700 text-sm">{{ $catatan->catatan }}</p>
                    </div>
                @empty
                    <p class="text-gray-500 text-center italic py-4">Belum ada tanggapan.</p>
                @endforelse
            </div>

            <!-- User Reply Form -->
            <form action="{{ route('pengaduan.response', $pengaduan->id) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="catatan" class="block text-sm font-medium text-gray-700 mb-2">Tambah Tanggapan /
                        Catatan</label>
                    <textarea name="catatan" id="catatan" rows="3"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-black focus:border-transparent transition"
                        placeholder="Tulis tanggapan..." required></textarea>
                </div>
                <button type="submit"
                    class="bg-black text-white px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-800 transition shadow-sm">
                    Kirim Tanggapan
                </button>
                @error('catatan')
                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                @enderror
            </form>
        </div>
    </div>
@endsection