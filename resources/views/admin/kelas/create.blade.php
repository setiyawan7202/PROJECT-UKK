@extends('layouts.admin')

@section('title', 'Tambah Kelas')

@section('content')
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('admin.kelas.index') }}"
                class="hidden lg:inline-flex items-center gap-2 text-gray-500 hover:text-gray-900 text-sm mb-4 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali ke Daftar Kelas
            </a>
            <h1 class="text-xl lg:text-2xl font-bold text-gray-900">Tambah Kelas Baru</h1>
            <p class="text-sm text-gray-500">Isi form berikut untuk menambahkan kelas baru</p>
        </div>

        <!-- Error Messages -->
        @if($errors->any())
            <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('admin.kelas.store') }}"
            class="bg-white rounded-xl lg:rounded-2xl border border-gray-100 p-6">
            @csrf

            <!-- Nama Kelas -->
            <div class="mb-5">
                <label for="nama_kelas" class="block text-sm font-medium text-gray-700 mb-2">Nama Kelas <span
                        class="text-red-500">*</span></label>
                <input type="text" id="nama_kelas" name="nama_kelas" value="{{ old('nama_kelas') }}" required
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-1 focus:ring-black transition"
                    placeholder="Masukan nama kelas">
            </div>

            <!-- Jurusan -->
            <div class="mb-5">
                <label for="jurusan" class="block text-sm font-medium text-gray-700 mb-2">Jurusan <span
                        class="text-red-500">*</span></label>
                <input type="text" id="jurusan" name="jurusan" value="{{ old('jurusan') }}" required
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-1 focus:ring-black transition"
                    placeholder="Masukan nama jurusan">
            </div>

            <!-- Tingkat -->
            <div class="mb-5">
                <label for="tingkat" class="block text-sm font-medium text-gray-700 mb-2">Tingkat <span
                        class="text-red-500">*</span></label>
                <select id="tingkat" name="tingkat" required
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-1 focus:ring-black transition bg-white">
                    <option value="">Pilih Tingkat</option>
                    <option value="X" {{ old('tingkat') === 'X' ? 'selected' : '' }}>X (Kelas 10)</option>
                    <option value="XI" {{ old('tingkat') === 'XI' ? 'selected' : '' }}>XI (Kelas 11)</option>
                    <option value="XII" {{ old('tingkat') === 'XII' ? 'selected' : '' }}>XII (Kelas 12)</option>
                </select>
            </div>

            <!-- Submit -->
            <div class="flex flex-col sm:flex-row gap-3">
                <button type="submit"
                    class="flex-1 py-3 px-6 bg-black text-white rounded-xl font-semibold hover:bg-gray-800 transition">
                    Simpan Kelas
                </button>
                <a href="{{ route('admin.kelas.index') }}"
                    class="py-3 px-6 border border-gray-200 text-gray-600 rounded-xl font-medium text-center hover:bg-gray-50 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection