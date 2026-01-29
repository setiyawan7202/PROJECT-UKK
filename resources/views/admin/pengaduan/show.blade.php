@extends('layouts.admin')

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.pengaduan.index') }}"
            class="inline-flex items-center gap-2 text-gray-500 hover:text-gray-900 mb-4 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Daftar
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Detail Pengaduan #{{ $pengaduan->id }}</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 mb-2">{{ $pengaduan->judul }}</h2>
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
                    <div class="text-right">
                        @if($pengaduan->status == 'pending')
                            <span
                                class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-sm font-medium">Pending</span>
                        @elseif($pengaduan->status == 'processed')
                            <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-sm font-medium">Diproses</span>
                        @elseif($pengaduan->status == 'completed')
                            <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm font-medium">Selesai</span>
                        @else
                            <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-sm font-medium">Ditolak</span>
                        @endif
                    </div>
                </div>

                <div class="prose prose-sm max-w-none text-gray-700 mb-6">
                    <h3 class="font-semibold text-gray-900 mb-1">Deskripsi:</h3>
                    <p class="whitespace-pre-line">{{ $pengaduan->deskripsi }}</p>
                </div>

                {{-- Lokasi Ruangan --}}
                @if($pengaduan->ruangan)
                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
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
                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
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
                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <h3 class="font-semibold text-gray-900 mb-2">Jenis Sarpras</h3>
                        <p class="text-gray-700">{{ $pengaduan->jenis_sarpras }}</p>
                    </div>
                @endif

                @if($pengaduan->foto)
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-2">Foto Bukti:</h3>
                        <img src="{{ Str::startsWith($pengaduan->foto, 'http') ? $pengaduan->foto : Storage::url($pengaduan->foto) }}"
                            alt="Bukti" class="rounded-lg border border-gray-200 max-h-96 object-contain">
                    </div>
                @endif
            </div>

            <!-- Responses -->
            <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm">
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

                <form action="{{ route('admin.pengaduan.response', $pengaduan->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="catatan" class="block text-sm font-medium text-gray-700 mb-2">Tambah Tanggapan /
                            Catatan</label>
                        <textarea name="catatan" id="catatan" rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-black focus:border-transparent transition"
                            placeholder="Tulis tanggapan untuk pelapor..." required></textarea>
                    </div>
                    <button type="submit"
                        class="bg-black text-white px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-800 transition shadow-sm">
                        Kirim Tanggapan
                    </button>
                </form>
            </div>
        </div>

        <!-- Sidebar Actions -->
        <div class="space-y-6">
            <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm">
                <h3 class="font-bold text-gray-900 mb-4">Aksi Pengaduan</h3>

                <form action="{{ route('admin.pengaduan.status', $pengaduan->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Update Status</label>
                        <select name="status"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-black focus:border-transparent transition bg-white mb-3">
                            <option value="pending" {{ $pengaduan->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processed" {{ $pengaduan->status == 'processed' ? 'selected' : '' }}>Diproses
                            </option>
                            <option value="completed" {{ $pengaduan->status == 'completed' ? 'selected' : '' }}>Selesai
                            </option>
                            <option value="rejected" {{ $pengaduan->status == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                        <button type="submit"
                            class="w-full bg-black text-white px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-800 transition shadow-sm">
                            Simpan Status
                        </button>
                    </div>
                </form>
            </div>

            <!-- Pelapor Info -->
            <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm">
                <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Informasi Pelapor
                </h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <span class="block text-gray-500 text-xs">Nama Lengkap</span>
                        <span class="font-medium text-gray-900">{{ $pengaduan->user->nama_lengkap ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="block text-gray-500 text-xs">Email</span>
                        <span class="font-medium text-gray-900">{{ $pengaduan->user->email }}</span>
                    </div>
                    <div>
                        <span class="block text-gray-500 text-xs">Role</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($pengaduan->user->role == 'admin') bg-red-100 text-red-700
                                                @elseif($pengaduan->user->role == 'staff') bg-blue-100 text-blue-700
                                                @elseif($pengaduan->user->role == 'guru') bg-green-100 text-green-700
                                                @else bg-gray-100 text-gray-700
                                                @endif">
                            {{ ucfirst($pengaduan->user->role) }}
                        </span>
                    </div>
                    @if($pengaduan->user->siswa)
                        <div>
                            <span class="block text-gray-500 text-xs">NISN</span>
                            <span class="font-medium text-gray-900">{{ $pengaduan->user->siswa->nisn ?? '-' }}</span>
                        </div>
                        @if($pengaduan->user->siswa->kelas)
                            <div>
                                <span class="block text-gray-500 text-xs">Kelas</span>
                                <span
                                    class="font-medium text-gray-900">{{ $pengaduan->user->siswa->kelas->nama_kelas ?? '-' }}</span>
                            </div>
                        @endif
                    @endif
                    @if($pengaduan->user->guru)
                        <div>
                            <span class="block text-gray-500 text-xs">NIP</span>
                            <span class="font-medium text-gray-900">{{ $pengaduan->user->guru->nip ?? '-' }}</span>
                        </div>
                        @if($pengaduan->user->guru->jabatan)
                            <div>
                                <span class="block text-gray-500 text-xs">Jabatan</span>
                                <span class="font-medium text-gray-900">{{ $pengaduan->user->guru->jabatan }}</span>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection