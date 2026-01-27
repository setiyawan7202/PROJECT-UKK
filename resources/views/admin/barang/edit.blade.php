@extends('layouts.admin')

@section('title', 'Edit Barang')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('admin.barang.index') }}"
                class="inline-flex items-center gap-2 text-gray-500 hover:text-gray-900 text-sm mb-4 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Edit Barang</h1>
            <p class="text-sm text-gray-500">Perbarui data barang</p>
        </div>

        @if($errors->any())
            <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white border border-gray-100 rounded-xl p-6 lg:p-8">
            <form action="{{ route('admin.barang.update', $barang->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Nama Barang -->
                <div>
                    <label for="nama_barang" class="block text-sm font-medium text-gray-700 mb-2">Nama Barang <span
                            class="text-red-500">*</span></label>
                    <input type="text" id="nama_barang" name="nama_barang"
                        value="{{ old('nama_barang', $barang->nama_barang) }}"
                        class="w-full px-4 py-3 border @error('nama_barang') border-red-500 @else border-gray-200 @enderror rounded-xl focus:outline-none focus:ring-1 focus:ring-black"
                        placeholder="Masukkan nama barang">
                    @error('nama_barang')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kategori & Ruangan -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="kategori_id" class="block text-sm font-medium text-gray-700 mb-2">Kategori <span
                                class="text-red-500">*</span></label>
                        <select id="kategori_id" name="kategori_id"
                            class="searchable-select w-full">
                            <option value="">Pilih Kategori</option>
                            @foreach($kategoris as $kategori)
                                <option value="{{ $kategori->id }}" {{ old('kategori_id', $barang->kategori_id) == $kategori->id ? 'selected' : '' }}>{{ $kategori->nama_kategori }}</option>
                            @endforeach
                        </select>
                        @error('kategori_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="ruangan_id" class="block text-sm font-medium text-gray-700 mb-2">Ruangan <span
                                class="text-red-500">*</span></label>
                        <select id="ruangan_id" name="ruangan_id"
                            class="searchable-select w-full">
                            <option value="">Pilih Ruangan</option>
                            @foreach($ruangans as $ruangan)
                                <option value="{{ $ruangan->id }}" {{ old('ruangan_id', $barang->ruangan_id) == $ruangan->id ? 'selected' : '' }}>{{ $ruangan->nama_ruangan }}</option>
                            @endforeach
                        </select>
                        @error('ruangan_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Jenis Aset -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Aset <span class="text-red-500">*</span></label>
                    <div class="flex gap-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="jenis_aset" value="tik" {{ old('jenis_aset', $barang->jenis_aset) == 'tik' ? 'checked' : '' }}
                                class="w-4 h-4 text-black border-gray-300 focus:ring-black">
                            <span class="text-sm text-gray-700">Aset TIK (Teknologi Informasi & Komunikasi)</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="jenis_aset" value="non_tik" {{ old('jenis_aset', $barang->jenis_aset) == 'non_tik' ? 'checked' : '' }}
                                class="w-4 h-4 text-black border-gray-300 focus:ring-black">
                            <span class="text-sm text-gray-700">Non-TIK</span>
                        </label>
                    </div>
                    @error('jenis_aset')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tipe Barang -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Barang <span
                            class="text-red-500">*</span></label>
                    <div class="flex gap-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="tipe_barang" value="maintenance" {{ old('tipe_barang', $barang->tipe_barang) == 'maintenance' ? 'checked' : '' }}
                                class="w-4 h-4 text-black border-gray-300 focus:ring-black">
                            <span class="text-sm text-gray-700">Bisa Diperbaiki</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="tipe_barang" value="disposable" {{ old('tipe_barang', $barang->tipe_barang) == 'disposable' ? 'checked' : '' }}
                                class="w-4 h-4 text-black border-gray-300 focus:ring-black">
                            <span class="text-sm text-gray-700">Sekali Pakai</span>
                        </label>
                    </div>
                    @error('tipe_barang')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Upload Gambar -->
                <div>
                    <label for="gambar" class="block text-sm font-medium text-gray-700 mb-2">Upload Gambar Barang</label>
                    
                    @if($barang->gambar)
                        <div class="mb-3">
                            <p class="text-xs text-gray-500 mb-2">Gambar saat ini:</p>
                            <img src="{{ asset('storage/' . $barang->gambar) }}" alt="Gambar barang" class="w-32 h-32 object-cover rounded-lg border border-gray-200">
                        </div>
                    @endif
                    
                    <input type="file" id="gambar" name="gambar" accept=".jpg,.jpeg,.png,.gif,.webp"
                        class="w-full px-4 py-3 border @error('gambar') border-red-500 @else border-gray-200 @enderror rounded-xl focus:outline-none focus:ring-1 focus:ring-black">
                    <p class="text-xs text-gray-500 mt-1">Format: JPG, JPEG, PNG, GIF, WEBP. Maksimal 2MB. Kosongkan jika tidak ingin mengubah gambar.</p>
                    @error('gambar')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-4 mt-8">
                    <button type="submit"
                        class="bg-black text-white px-6 py-3 rounded-xl font-medium hover:bg-gray-800 transition">Simpan
                        Perubahan</button>
                    <a href="{{ route('admin.barang.show', $barang->id) }}"
                        class="text-gray-600 font-medium hover:text-gray-900">Lihat Detail Unit</a>
                </div>
            </form>

            <div class="mt-8 pt-6 border-t border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-red-600">Hapus Barang</h3>
                        <p class="text-sm text-gray-500">Tindakan ini tidak dapat dibatalkan. Semua data unit terkait juga akan terhapus.</p>
                    </div>
                    <form action="{{ route('admin.barang.destroy', $barang->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus barang ini? Semua data terkait akan hilang permanen.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-50 text-red-600 px-6 py-2.5 rounded-xl font-medium hover:bg-red-100 transition border border-red-200">
                            Hapus Barang
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection