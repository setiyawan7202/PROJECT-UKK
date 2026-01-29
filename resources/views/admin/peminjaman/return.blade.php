@extends('layouts.admin')

@section('content')
    <div class="max-w-2xl mx-auto">
        <a href="{{ route('admin.peminjaman.index') }}"
            class="inline-flex items-center gap-2 text-gray-500 hover:text-gray-900 mb-6 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Daftar
        </a>

        <div class="bg-white rounded-xl border border-gray-100 p-6 sm:p-8 shadow-sm">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Proses Pengembalian Barang</h1>
            <p class="text-gray-500 mb-6">Pastikan kondisi barang telah dicek sebelum memproses pengembalian.</p>

            <!-- Loan Details -->
            <div class="bg-gray-50 rounded-lg p-4 mb-6 border border-gray-200">
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="block text-gray-500 text-xs">Peminjam</span>
                        <span
                            class="font-medium font-gray-900">{{ $peminjaman->user->nama_lengkap ?? $peminjaman->user->email }}</span>
                    </div>
                    <div>
                        <span class="block text-gray-500 text-xs">Barang</span>
                        <span class="font-medium font-gray-900">{{ $peminjaman->barang->nama_barang }}</span>
                    </div>
                    <div>
                        <span class="block text-gray-500 text-xs">Kode Unit</span>
                        <span
                            class="font-mono bg-white px-2 py-0.5 rounded border border-gray-300">{{ $peminjaman->barangUnit->kode_unit ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="block text-gray-500 text-xs">Tenggat Waktu</span>
                        <span
                            class="font-medium {{ $peminjaman->tgl_kembali_rencana < now() ? 'text-red-600' : 'text-gray-900' }}">
                            {{ $peminjaman->tgl_kembali_rencana->format('d M Y') }}
                        </span>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.peminjaman.storeReturn', $peminjaman->id) }}" method="POST"
                enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Tanggal Kembali -->
                <div>
                    <label for="tgl_kembali" class="block text-sm font-medium text-gray-700 mb-2">Tanggal
                        Dikembalikan</label>
                    <input type="date" name="tgl_kembali" id="tgl_kembali" value="{{ date('Y-m-d') }}"
                        class="w-full rounded-lg border-gray-300 focus:border-black focus:ring-black transition" required>
                    @error('tgl_kembali')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kondisi -->
                <div>
                    <label for="kondisi" class="block text-sm font-medium text-gray-700 mb-2">Kondisi Barang</label>
                    <select name="kondisi" id="kondisi"
                        class="w-full rounded-lg border-gray-300 focus:border-black focus:ring-black transition" required>
                        <option value="baik">Baik (Layak Pakai)</option>
                        <option value="rusak_ringan">Rusak Ringan (Perlu Perbaikan)</option>
                        <option value="rusak_berat">Rusak Berat (Tidak Layak)</option>
                        <option value="hilang">Hilang</option>
                    </select>
                    @error('kondisi')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Keterangan -->
                <div>
                    <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">Keterangan /
                        Catatan</label>
                    <textarea name="keterangan" id="keterangan" rows="3"
                        class="w-full rounded-lg border-gray-300 focus:border-black focus:ring-black transition"
                        placeholder="Catatan tambahan mengenai kondisi barang..."></textarea>
                    @error('keterangan')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Denda -->
                <div>
                    <label for="denda" class="block text-sm font-medium text-gray-700 mb-2">Denda (Opsional)</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                        <input type="number" name="denda" id="denda" min="0" step="500"
                            class="pl-10 w-full rounded-lg border-gray-300 focus:border-black focus:ring-black transition"
                            placeholder="0">
                    </div>
                </div>

                <!-- Foto -->
                <div>
                    <label for="foto" class="block text-sm font-medium text-gray-700 mb-2">Foto Barang (Opsional)</label>
                    <input type="file" name="foto" id="foto" accept="image/*"
                        class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                    @error('foto')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4">
                    <button type="submit"
                        class="w-full bg-black text-white py-3 rounded-lg font-semibold hover:bg-gray-800 transition">
                        Proses Pengembalian
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection