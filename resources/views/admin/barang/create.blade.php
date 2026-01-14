@extends('layouts.admin')

@section('title', 'Tambah Barang')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Tambah Barang</h1>
            <p class="text-sm text-gray-500">Input data barang baru</p>
        </div>

        <div class="bg-white border border-gray-100 rounded-xl p-6 lg:p-8">
            <form action="{{ route('admin.barang.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Kode Barang -->
                <div>
                    <label for="kode_barang" class="block text-sm font-medium text-gray-700 mb-2">Kode Barang <span class="text-red-500">*</span></label>
                    <input type="text" id="kode_barang" name="kode_barang" value="{{ old('kode_barang') }}" 
                        class="w-full px-4 py-3 border @error('kode_barang') border-red-500 @else border-gray-200 @enderror rounded-xl focus:outline-none focus:ring-1 focus:ring-black"
                        placeholder="Masukkan kode barang">
                    @error('kode_barang')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nama Barang -->
                <div>
                    <label for="nama_barang" class="block text-sm font-medium text-gray-700 mb-2">Nama Barang <span class="text-red-500">*</span></label>
                    <input type="text" id="nama_barang" name="nama_barang" value="{{ old('nama_barang') }}"
                        class="w-full px-4 py-3 border @error('nama_barang') border-red-500 @else border-gray-200 @enderror rounded-xl focus:outline-none focus:ring-1 focus:ring-black"
                        placeholder="Masukkan nama barang">
                    @error('nama_barang')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kategori & Ruangan -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="kategori_id" class="block text-sm font-medium text-gray-700 mb-2">Kategori <span class="text-red-500">*</span></label>
                        <select id="kategori_id" name="kategori_id" class="w-full px-4 py-3 border @error('kategori_id') border-red-500 @else border-gray-200 @enderror rounded-xl bg-white focus:outline-none focus:ring-1 focus:ring-black">
                            <option value="">Pilih Kategori</option>
                            @foreach($kategoris as $kategori)
                                <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>{{ $kategori->nama_kategori }}</option>
                            @endforeach
                        </select>
                        @error('kategori_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="ruangan_id" class="block text-sm font-medium text-gray-700 mb-2">Ruangan <span class="text-red-500">*</span></label>
                        <select id="ruangan_id" name="ruangan_id" class="w-full px-4 py-3 border @error('ruangan_id') border-red-500 @else border-gray-200 @enderror rounded-xl bg-white focus:outline-none focus:ring-1 focus:ring-black">
                            <option value="">Pilih Ruangan</option>
                            @foreach($ruangans as $ruangan)
                                <option value="{{ $ruangan->id }}" {{ old('ruangan_id') == $ruangan->id ? 'selected' : '' }}>{{ $ruangan->nama_ruangan }}</option>
                            @endforeach
                        </select>
                        @error('ruangan_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Stok & Kondisi -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="jumlah_stok" class="block text-sm font-medium text-gray-700 mb-2">Jumlah Stok <span class="text-red-500">*</span></label>
                        <input type="number" id="jumlah_stok" name="jumlah_stok" value="{{ old('jumlah_stok', 0) }}" min="0"
                            class="w-full px-4 py-3 border @error('jumlah_stok') border-red-500 @else border-gray-200 @enderror rounded-xl focus:outline-none focus:ring-1 focus:ring-black">
                        @error('jumlah_stok')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="kondisi_saat_ini" class="block text-sm font-medium text-gray-700 mb-2">Kondisi <span class="text-red-500">*</span></label>
                        <select id="kondisi_saat_ini" name="kondisi_saat_ini" class="w-full px-4 py-3 border @error('kondisi_saat_ini') border-red-500 @else border-gray-200 @enderror rounded-xl bg-white focus:outline-none focus:ring-1 focus:ring-black">
                            <option value="Baik" {{ old('kondisi_saat_ini') == 'Baik' ? 'selected' : '' }}>Baik</option>
                            <option value="Rusak Ringan" {{ old('kondisi_saat_ini') == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                            <option value="Rusak Berat" {{ old('kondisi_saat_ini') == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                        </select>
                        @error('kondisi_saat_ini')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center gap-4 mt-8">
                    <button type="submit" class="bg-black text-white px-6 py-3 rounded-xl font-medium hover:bg-gray-800 transition">Simpan Barang</button>
                    <a href="{{ route('admin.barang.index') }}" class="text-gray-600 font-medium hover:text-gray-900">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
