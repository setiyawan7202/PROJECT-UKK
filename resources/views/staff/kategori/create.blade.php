@extends('layouts.staff')

@section('title', 'Tambah Kategori')

@section('content')
    <div class="max-w-xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('staff.kategori.index') }}"
                class="inline-flex items-center gap-2 text-gray-500 hover:text-gray-900 text-sm mb-4 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Tambah Kategori</h1>
            <p class="text-sm text-gray-500">Buat kategori barang baru</p>
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
            <form action="{{ route('staff.kategori.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="kode_kategori" class="block text-sm font-medium text-gray-700 mb-2">Kode Kategori <span
                            class="text-red-500">*</span></label>
                    <input type="text" id="kode_kategori" name="kode_kategori" value="{{ old('kode_kategori') }}"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-1 focus:ring-black"
                        placeholder="Masukkan kode kategori">
                </div>

                <div>
                    <label for="nama_kategori" class="block text-sm font-medium text-gray-700 mb-2">Nama Kategori <span
                            class="text-red-500">*</span></label>
                    <input type="text" id="nama_kategori" name="nama_kategori" value="{{ old('nama_kategori') }}"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-1 focus:ring-black"
                        placeholder="Masukkan nama kategori">
                </div>

                <div>
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi" rows="3"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-1 focus:ring-black">{{ old('deskripsi') }}</textarea>
                </div>

                <div class="flex items-center gap-4 mt-8">
                    <button type="submit"
                        class="bg-black text-white px-6 py-3 rounded-xl font-medium hover:bg-gray-800 transition">Simpan</button>
                    <a href="{{ route('staff.kategori.index') }}"
                        class="text-gray-600 font-medium hover:text-gray-900">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection