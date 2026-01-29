@extends('layouts.main')

@section('title', 'Keranjang Peminjaman')

@section('content')
    <div class="max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Keranjang Peminjaman</h1>

        @if(empty($cart))
            <div class="bg-white rounded-xl border border-dashed border-gray-300 p-12 text-center">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Keranjang Kosong</h3>
                <p class="text-gray-500 mb-6">Anda belum menambahkan barang apapun ke keranjang.</p>
                <a href="{{ route('katalog.index') }}"
                    class="inline-flex items-center px-6 py-3 bg-black text-white font-medium rounded-lg hover:bg-gray-800 transition">
                    Lihat Katalog
                </a>
            </div>
        @else
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Cart Items -->
                <div class="flex-1">
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden mb-4">
                        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                            <h2 class="font-bold text-gray-900">Daftar Item ({{ count($cart) }})</h2>
                            <a href="{{ route('katalog.index') }}"
                                class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                + Tambah Barang Lain
                            </a>
                        </div>
                        <ul class="divide-y divide-gray-100">
                            @foreach($cart as $id => $item)
                                <li class="p-6 flex items-start gap-4">
                                    <div
                                        class="w-20 h-20 bg-gray-50 rounded-lg flex-shrink-0 flex items-center justify-center overflow-hidden">
                                        @if($item['gambar'])
                                            <img src="{{ Str::startsWith($item['gambar'], 'http') ? $item['gambar'] : asset('storage/' . $item['gambar']) }}"
                                                alt="{{ $item['barang_name'] }}" class="w-full h-full object-cover">
                                        @else
                                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-bold text-gray-900 mb-1">{{ $item['barang_name'] }}</h3>
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="bg-gray-100 text-gray-600 text-xs px-2 py-0.5 rounded font-mono">
                                                {{ $item['unit_code'] }}
                                            </span>
                                            <span class="text-xs text-gray-500">
                                                {{ $item['kondisi'] }}
                                            </span>
                                        </div>
                                    </div>
                                    <form action="{{ route('cart.remove') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="unit_id" value="{{ $item['unit_id'] }}">
                                        <button type="submit"
                                            class="text-red-500 hover:text-red-700 p-2 rounded-full hover:bg-red-50 transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Checkout Form -->
                <div class="lg:w-96 flex-shrink-0">
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 sticky top-24">
                        <h2 class="font-bold text-gray-900 mb-4">Informasi Peminjaman</h2>
                        <form action="{{ route('cart.checkout') }}" method="POST">
                            @csrf

                            <!-- Tanggal Pinjam -->
                            <div class="mb-4">
                                <label for="tgl_pinjam" class="block text-sm font-medium text-gray-700 mb-1">Tanggal
                                    Pinjam</label>
                                <input type="date" name="tgl_pinjam" id="tgl_pinjam" value="{{ old('tgl_pinjam', $today) }}"
                                    required class="w-full rounded-lg border-gray-300 focus:border-black focus:ring-black">
                                @error('tgl_pinjam') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Tanggal Kembali -->
                            <div class="mb-4">
                                <label for="tgl_kembali_rencana" class="block text-sm font-medium text-gray-700 mb-1">Tanggal
                                    Kembali</label>
                                <input type="date" name="tgl_kembali_rencana" id="tgl_kembali_rencana"
                                    value="{{ old('tgl_kembali_rencana', $tomorrow) }}" required
                                    class="w-full rounded-lg border-gray-300 focus:border-black focus:ring-black">
                                @error('tgl_kembali_rencana') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Tujuan -->
                            <div class="mb-6">
                                <label for="tujuan_pinjam" class="block text-sm font-medium text-gray-700 mb-1">Tujuan
                                    Peminjaman</label>
                                <textarea name="tujuan_pinjam" id="tujuan_pinjam" rows="3" required
                                    placeholder="Contoh: Untuk kegiatan praktikum..."
                                    class="w-full rounded-lg border-gray-300 focus:border-black focus:ring-black">{{ old('tujuan_pinjam') }}</textarea>
                                @error('tujuan_pinjam') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <button type="submit"
                                class="w-full bg-black text-white py-3 rounded-lg font-bold hover:bg-gray-800 transition shadow-lg shadow-gray-200">
                                Ajukan Peminjaman
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection