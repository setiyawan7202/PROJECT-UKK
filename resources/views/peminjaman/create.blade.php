@extends('layouts.app')

@section('title', 'Ajukan Peminjaman')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <!-- Header -->
            <div class="mb-6">
                <a href="{{ route('peminjaman.index') }}"
                    class="inline-flex items-center gap-2 text-gray-500 hover:text-gray-900 text-sm mb-4 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali ke Riwayat
                </a>
                <h1 class="text-xl lg:text-2xl font-bold text-gray-900">Ajukan Peminjaman</h1>
                <p class="text-sm text-gray-500">Isi form untuk mengajukan peminjaman barang</p>
            </div>

            @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('peminjaman.store') }}"
                class="bg-white rounded-xl border border-gray-100 p-6">
                @csrf

                <!-- Barang -->
                <div class="mb-5">
                    <label for="barang_id" class="block text-sm font-medium text-gray-700 mb-2">Pilih Barang <span
                            class="text-red-500">*</span></label>
                    <select id="barang_id" name="barang_id" required
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-1 focus:ring-black transition bg-white">
                        <option value="">-- Pilih Barang --</option>
                        @foreach($barangs as $barang)
                            <option value="{{ $barang->id }}" {{ old('barang_id') == $barang->id ? 'selected' : '' }}>
                                {{ $barang->nama_barang }} (Stok: {{ $barang->jumlah_stok }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tanggal Pinjam -->
                <div class="mb-5">
                    <label for="tgl_pinjam" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Pinjam <span
                            class="text-red-500">*</span></label>
                    <input type="date" id="tgl_pinjam" name="tgl_pinjam" value="{{ old('tgl_pinjam') }}" required
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-1 focus:ring-black transition">
                </div>

                <!-- Tanggal Kembali -->
                <div class="mb-5">
                    <label for="tgl_kembali_rencana" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Kembali
                        (Rencana) <span class="text-red-500">*</span></label>
                    <input type="date" id="tgl_kembali_rencana" name="tgl_kembali_rencana"
                        value="{{ old('tgl_kembali_rencana') }}" required
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-1 focus:ring-black transition">
                </div>

                <!-- Tujuan -->
                <div class="mb-5">
                    <label for="tujuan_pinjam" class="block text-sm font-medium text-gray-700 mb-2">Tujuan Pinjam <span
                            class="text-red-500">*</span></label>
                    <textarea id="tujuan_pinjam" name="tujuan_pinjam" rows="3" required
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-1 focus:ring-black transition"
                        placeholder="Jelaskan tujuan peminjaman">{{ old('tujuan_pinjam') }}</textarea>
                </div>

                <!-- Submit -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <button type="submit"
                        class="flex-1 py-3 px-6 bg-black text-white rounded-xl font-semibold hover:bg-gray-800 transition">
                        Ajukan Peminjaman
                    </button>
                    <a href="{{ route('peminjaman.index') }}"
                        class="py-3 px-6 border border-gray-200 text-gray-600 rounded-xl font-medium text-center hover:bg-gray-50 transition">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection