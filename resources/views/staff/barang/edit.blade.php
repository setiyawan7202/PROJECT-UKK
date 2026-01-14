@extends('layouts.staff')

@section('title', 'Edit Barang')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('staff.barang.index') }}"
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
            <form action="{{ route('staff.barang.update', $barang->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="kode_barang" class="block text-sm font-medium text-gray-700 mb-2">Kode Barang <span
                            class="text-red-500">*</span></label>
                    <input type="text" id="kode_barang" name="kode_barang"
                        value="{{ old('kode_barang', $barang->kode_barang) }}"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-1 focus:ring-black"
                        placeholder="Masukkan kode barang">
                </div>

                <div>
                    <label for="nama_barang" class="block text-sm font-medium text-gray-700 mb-2">Nama Barang <span
                            class="text-red-500">*</span></label>
                    <input type="text" id="nama_barang" name="nama_barang"
                        value="{{ old('nama_barang', $barang->nama_barang) }}"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-1 focus:ring-black"
                        placeholder="Masukkan nama barang">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="kategori_id" class="block text-sm font-medium text-gray-700 mb-2">Kategori <span
                                class="text-red-500">*</span></label>
                        <select id="kategori_id" name="kategori_id"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-white focus:outline-none focus:ring-1 focus:ring-black">
                            <option value="">Pilih Kategori</option>
                            @foreach($kategoris as $kategori)
                                <option value="{{ $kategori->id }}" {{ old('kategori_id', $barang->kategori_id) == $kategori->id ? 'selected' : '' }}>{{ $kategori->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="ruangan_id" class="block text-sm font-medium text-gray-700 mb-2">Ruangan <span
                                class="text-red-500">*</span></label>
                        <select id="ruangan_id" name="ruangan_id"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-white focus:outline-none focus:ring-1 focus:ring-black">
                            <option value="">Pilih Ruangan</option>
                            @foreach($ruangans as $ruangan)
                                <option value="{{ $ruangan->id }}" {{ old('ruangan_id', $barang->ruangan_id) == $ruangan->id ? 'selected' : '' }}>{{ $ruangan->nama_ruangan }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="jumlah_stok" class="block text-sm font-medium text-gray-700 mb-2">Jumlah Stok <span
                                class="text-red-500">*</span></label>
                        <input type="number" id="jumlah_stok" name="jumlah_stok"
                            value="{{ old('jumlah_stok', $barang->jumlah_stok) }}" min="0"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-1 focus:ring-black">
                    </div>
                    <div>
                        <label for="kondisi_saat_ini" class="block text-sm font-medium text-gray-700 mb-2">Kondisi <span
                                class="text-red-500">*</span></label>
                        <select id="kondisi_saat_ini" name="kondisi_saat_ini"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-white focus:outline-none focus:ring-1 focus:ring-black">
                            <option value="Baik" {{ old('kondisi_saat_ini', $barang->kondisi_saat_ini) == 'Baik' ? 'selected' : '' }}>Baik</option>
                            <option value="Rusak Ringan" {{ old('kondisi_saat_ini', $barang->kondisi_saat_ini) == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                            <option value="Rusak Berat" {{ old('kondisi_saat_ini', $barang->kondisi_saat_ini) == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center gap-4 mt-8">
                    <button type="submit"
                        class="bg-black text-white px-6 py-3 rounded-xl font-medium hover:bg-gray-800 transition">Simpan
                        Perubahan</button>
                    <a href="{{ route('staff.barang.index') }}"
                        class="text-gray-600 font-medium hover:text-gray-900">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection