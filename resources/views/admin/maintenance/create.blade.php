@extends('layouts.admin')

@section('title', 'Buat Jadwal Maintenance')

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.maintenance.index') }}"
            class="text-gray-500 hover:text-gray-700 text-sm flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali
        </a>
        <h1 class="text-xl lg:text-2xl font-bold text-gray-900 mt-2">Buat Jadwal Maintenance</h1>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 p-6">
        <form action="{{ route('admin.maintenance.store') }}" method="POST">
            @csrf

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-3">Tipe Target</label>
                <div class="flex gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="type" value="kategori" checked class="w-4 h-4 text-black focus:ring-black"
                            onchange="toggleTarget()">
                        <span class="text-sm">Per Kategori</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="type" value="barang" class="w-4 h-4 text-black focus:ring-black"
                            onchange="toggleTarget()">
                        <span class="text-sm">Per Barang Spesifik</span>
                    </label>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div id="kategoriField">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                    <select name="kategori_id"
                        class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent">
                        <option value="">Pilih Kategori</option>
                        @foreach($kategoris as $kategori)
                            <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>

                <div id="barangField" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Barang</label>
                    <select name="barang_id"
                        class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent">
                        <option value="">Pilih Barang</option>
                        @foreach($barangs as $barang)
                            <option value="{{ $barang->id }}">{{ $barang->nama_barang }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Interval (Hari)</label>
                    <input type="number" name="interval_days" value="{{ old('interval_days', 90) }}" min="1" required
                        class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">Contoh: 90 = 3 bulan, 180 = 6 bulan</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Maintenance Pertama</label>
                    <input type="date" name="next_maintenance_at"
                        value="{{ old('next_maintenance_at', now()->addDays(90)->format('Y-m-d')) }}" required
                        class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent">
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.maintenance.index') }}"
                    class="px-4 py-2 border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 bg-black text-white rounded-lg hover:bg-gray-800 transition">
                    Simpan Jadwal
                </button>
            </div>
        </form>
    </div>

    <script>
        function toggleTarget() {
            const type = document.querySelector('input[name="type"]:checked').value;
            document.getElementById('kategoriField').classList.toggle('hidden', type !== 'kategori');
            document.getElementById('barangField').classList.toggle('hidden', type !== 'barang');
        }
    </script>
@endsection