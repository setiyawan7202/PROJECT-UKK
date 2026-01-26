@extends('layouts.admin')

@section('title', 'Tambah Kategori')

@section('content')
    <div class="max-w-xl mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Tambah Kategori</h1>
            <p class="text-sm text-gray-500">Buat kategori barang baru</p>
        </div>

        <div class="bg-white border border-gray-100 rounded-xl p-6 lg:p-8">
            <form action="{{ route('admin.kategori.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="kode_kategori" class="block text-sm font-medium text-gray-700 mb-2">Kode Kategori <span class="text-red-500">*</span></label>
                    <input type="text" id="kode_kategori" name="kode_kategori" value="{{ old('kode_kategori', $generatedKode) }}"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 text-gray-600 focus:outline-none cursor-not-allowed"
                        readonly>
                    <p class="text-gray-400 text-xs mt-1">Kode di-generate otomatis</p>
                    @error('kode_kategori')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="nama_kategori" class="block text-sm font-medium text-gray-700 mb-2">Nama Kategori <span class="text-red-500">*</span></label>
                    <input type="text" id="nama_kategori" name="nama_kategori" value="{{ old('nama_kategori') }}"
                        class="w-full px-4 py-3 border @error('nama_kategori') border-red-500 @else border-gray-200 @enderror rounded-xl focus:outline-none focus:ring-1 focus:ring-black"
                        placeholder="Masukkan nama kategori">
                    @error('nama_kategori')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi" rows="3"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-1 focus:ring-black">{{ old('deskripsi') }}</textarea>
                </div>

                <div class="flex items-center gap-4 mt-8">
                    <button type="submit" class="bg-black text-white px-6 py-3 rounded-xl font-medium hover:bg-gray-800 transition">Simpan</button>
                    <a href="{{ route('admin.kategori.index') }}" class="text-gray-600 font-medium hover:text-gray-900">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection

