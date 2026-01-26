@extends('layouts.app')

@section('title', 'Katalog Barang')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="bg-black rounded-2xl p-6 sm:p-10 mb-8 text-white relative overflow-hidden">
             <div class="absolute top-0 right-0 p-4 opacity-10 transform translate-x-10 -translate-y-10">
                <svg class="w-64 h-64" fill="currentColor" viewBox="0 0 24 24">
                     <path d="M4 7v10c0 2 3.5 4 8 4s8-2 8-4V7M4 7c0 2 3.5 4 8 4s8-2 8-4M4 7c0-2 3.5-4 8-4s8 2 8 4" />
                </svg>
             </div>
            <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
                <div>
                    <h1 class="text-3xl sm:text-4xl font-bold mb-2">Katalog Barang</h1>
                    <p class="text-gray-400 max-w-xl">
                        Temukan berbagai sarana dan prasarana yang tersedia untuk menunjang kegiatan Anda.
                        Gunakan fitur pencarian untuk menemukan barang dengan cepat.
                    </p>
                </div>
            </div>
        </div>

        <!-- Filter & Search Bar -->
        <div class="sticky top-20 z-30 bg-white/80 backdrop-blur-md border border-gray-100 rounded-xl shadow-sm p-4 mb-8">
            <form method="GET" action="{{ route('katalog.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Filter Kategori -->
                <div>
                    <select name="kategori_id" 
                        class="w-full rounded-lg border-gray-200 bg-gray-50 focus:border-black focus:ring-black transition py-2.5 text-sm"
                        onchange="this.form.submit()">
                        <option value="">Semua Kategori</option>
                        @foreach($kategoris as $kategori)
                            <option value="{{ $kategori->id }}" {{ request('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                {{ $kategori->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Ruangan -->
                <div>
                    <select name="ruangan_id" 
                        class="w-full rounded-lg border-gray-200 bg-gray-50 focus:border-black focus:ring-black transition py-2.5 text-sm"
                        onchange="this.form.submit()">
                        <option value="">Semua Ruangan</option>
                        @foreach($ruangans as $ruangan)
                            <option value="{{ $ruangan->id }}" {{ request('ruangan_id') == $ruangan->id ? 'selected' : '' }}>
                                {{ $ruangan->nama_ruangan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Jenis Aset -->
                <div>
                    <select name="jenis_aset" 
                        class="w-full rounded-lg border-gray-200 bg-gray-50 focus:border-black focus:ring-black transition py-2.5 text-sm"
                        onchange="this.form.submit()">
                        <option value="">Semua Aset</option>
                        <option value="tik" {{ request('jenis_aset') == 'tik' ? 'selected' : '' }}>Aset TIK</option>
                        <option value="non_tik" {{ request('jenis_aset') == 'non_tik' ? 'selected' : '' }}>Non-TIK</option>
                    </select>
                </div>

                <!-- Search -->
                <div class="flex gap-2">
                    <div class="relative flex-1">
                        <input type="text" name="search" value="{{ request('search') }}" 
                            placeholder="Cari nama barang..."
                            class="w-full rounded-lg border-gray-200 bg-gray-50 focus:border-black focus:ring-black pl-10 py-2.5 text-sm transition">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
            </form>
        </div>

        <!-- Grid Barang -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($barangs as $barang)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition duration-300 group">
                    <div class="h-56 bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center relative overflow-hidden group-hover:bg-gray-100 transition-colors">
                        <!-- Image or Default Icon -->
                        @if($barang->gambar)
                            <img src="{{ asset('storage/' . $barang->gambar) }}" alt="{{ $barang->nama_barang }}" 
                                class="w-full h-full object-cover transform group-hover:scale-110 transition duration-500">
                        @else
                             <div class="transform group-hover:scale-110 transition duration-500">
                                <svg class="w-20 h-20 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                             </div>
                        @endif
                        
                        <!-- Badges -->
                        <div class="absolute top-4 right-4 flex flex-col gap-2 items-end">
                            @if($barang->jenis_aset == 'tik')
                                <span class="bg-black text-white text-[10px] font-bold px-2.5 py-1 rounded-full shadow-sm">
                                    TIK
                                </span>
                            @endif
                            <span class="bg-white/90 backdrop-blur-sm text-gray-700 text-[10px] font-bold px-2.5 py-1 rounded-full shadow-sm border border-gray-100">
                                {{ $barang->kategori->nama_kategori }}
                            </span>
                        </div>
                        
                         <!-- Quick Info Overlay -->
                        <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300">
                             @if($barang->jumlah_stok > 0)
                                <a href="{{ route('peminjaman.create', ['barang_id' => $barang->id]) }}" 
                                   class="bg-white text-black px-6 py-2.5 rounded-full font-bold transform translate-y-4 group-hover:translate-y-0 transition duration-300 hover:scale-105">
                                    Pinjam Sekarang
                                </a>
                             @else
                                <span class="bg-red-500 text-white px-6 py-2 rounded-full font-bold text-sm transform translate-y-4 group-hover:translate-y-0 transition duration-300">
                                    Stok Habis
                                </span>
                             @endif
                        </div>
                    </div>
                    
                    <div class="p-5">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="font-bold text-gray-900 text-lg leading-tight line-clamp-2 min-h-[50px] group-hover:text-blue-600 transition">
                                {{ $barang->nama_barang }}
                            </h3>
                        </div>
                        
                        <div class="w-full h-px bg-gray-50 my-3"></div>

                        <div class="flex items-center justify-between">
                            <div class="flex flex-col">
                                <span class="text-xs text-gray-400 font-medium">Ketersediaan</span>
                                <span class="font-bold {{ $barang->jumlah_stok > 0 ? 'text-green-600' : 'text-red-500' }}">
                                    {{ $barang->jumlah_stok }} Unit
                                </span>
                            </div>
                             @if($barang->jumlah_stok > 0)
                                <div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 group-hover:bg-black group-hover:text-white transition">
                                     <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </div>
                             @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full flex flex-col items-center justify-center py-16 text-center bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Tidak ada barang ditemukan</h3>
                    <p class="text-gray-500 max-w-sm mx-auto">
                        Coba gunakan kata kunci lain atau ubah filter kategori untuk menemukan barang yang Anda cari.
                    </p>
                    <a href="{{ route('katalog.index') }}" class="mt-6 text-black font-semibold hover:underline">
                        Reset Filter
                    </a>
                </div>
            @endforelse
        </div>

        <div class="mt-8 flex justify-center">
            {{ $barangs->withQueryString()->links() }}
        </div>
    </div>
@endsection