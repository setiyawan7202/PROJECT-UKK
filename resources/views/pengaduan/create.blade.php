@extends('layouts.main')

@section('title', 'Buat Pengaduan - SIAPRAS')

@section('content')
    <div class="max-w-2xl mx-auto">
        <a href="{{ route('pengaduan.index') }}"
            class="inline-flex items-center gap-2 text-gray-500 hover:text-gray-900 mb-6 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>

        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Buat Pengaduan</h1>
            <p class="text-gray-500">Laporkan kerusakan sarpras yang Anda temukan.</p>
        </div>

        <form action="{{ route('pengaduan.store') }}" method="POST" enctype="multipart/form-data"
            class="bg-white rounded-xl border border-gray-100 p-6 sm:p-8 shadow-sm">
            @csrf

            <!-- Judul -->
            <div class="mb-5">
                <label for="judul" class="block text-sm font-medium text-gray-700 mb-2">
                    Judul Masalah <span class="text-red-500">*</span>
                </label>
                <input type="text" name="judul" id="judul"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-black focus:border-transparent transition"
                    placeholder="Tulis masalah" value="{{ old('judul') }}" required>
                @error('judul')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Lokasi Sarpras (Ruangan) -->
            <div class="mb-5">
                <label for="ruangan_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Lokasi Sarpras <span class="text-red-500">*</span>
                </label>
                <select name="ruangan_id" id="ruangan_id" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-black focus:border-transparent transition bg-white select2-ruangan">
                    <option value="">Pilih Ruangan</option>
                    @foreach($ruangans as $ruangan)
                        <option value="{{ $ruangan->id }}" {{ old('ruangan_id') == $ruangan->id ? 'selected' : '' }}>
                            {{ $ruangan->nama_ruangan }}
                        </option>
                    @endforeach
                </select>
                @error('ruangan_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Jenis Sarpras (Barang) - shown after ruangan selected -->
            <div class="mb-5" id="barang-container" style="display: none;">
                <label for="barang_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Barang Terdampak <span class="text-gray-400 font-normal">(Opsional)</span>
                </label>
                <select name="barang_id" id="barang_id"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-black focus:border-transparent transition bg-white">
                    <option value="">-- Pengaduan Ruangan (tanpa barang) --</option>
                </select>
                <p class="text-xs text-gray-500 mt-1">Kosongkan jika mengadukan masalah ruangan (AC, lampu, dll). Pilih
                    barang jika mengadukan kerusakan barang tertentu.</p>
                @error('barang_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Unit Barang (Dynamic) -->
            <div class="mb-5" id="unit-container" style="display: none;">
                <label for="barang_unit_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Pilih Unit Barang Spesifik
                </label>
                <select name="barang_unit_id" id="barang_unit_id"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-black focus:border-transparent transition bg-white">
                    <option value="">Pilih Unit</option>
                </select>
                <p class="text-xs text-gray-500 mt-1">Pilih unit barang yang spesifik jika diketahui</p>
                @error('barang_unit_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Kondisi -->
            <div class="mb-5">
                <label for="kondisi" class="block text-sm font-medium text-gray-700 mb-2">
                    Kondisi Sarpras
                </label>
                <select name="kondisi" id="kondisi"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-black focus:border-transparent transition bg-white">
                    <option value="">Pilih Kondisi</option>
                    <option value="baik" {{ old('kondisi') == 'baik' ? 'selected' : '' }}>Baik</option>
                    <option value="rusak_ringan" {{ old('kondisi') == 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan
                    </option>
                    <option value="rusak_berat" {{ old('kondisi') == 'rusak_berat' ? 'selected' : '' }}>Rusak Berat
                    </option>
                    <option value="hilang" {{ old('kondisi') == 'hilang' ? 'selected' : '' }}>Hilang</option>
                </select>
                @error('kondisi')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Deskripsi -->
            <div class="mb-5">
                <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                    Deskripsi Detail <span class="text-red-500">*</span>
                </label>
                <textarea name="deskripsi" id="deskripsi" rows="4"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-black focus:border-transparent transition"
                    placeholder="Jelaskan kerusakan secara detail..." required>{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Foto -->
            <div class="mb-5">
                <label for="foto" class="block text-sm font-medium text-gray-700 mb-2">Foto Bukti</label>
                <input type="file" name="foto" id="foto" accept="image/*"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-black focus:border-transparent transition file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-black file:text-white hover:file:bg-gray-800 file:cursor-pointer">
                <p class="text-xs text-gray-500 mt-2">Format: JPG, PNG, JPEG. Max: 2MB.</p>
                @error('foto')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-4">
                <button type="submit"
                    class="w-full bg-black text-white py-3 rounded-lg font-semibold hover:bg-gray-800 transition shadow-sm">
                    Kirim Pengaduan
                </button>
            </div>
    </div>
    </form>
    </div>

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Select2 JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function () {
            // Initialize Select2 for Ruangan dropdown
            $('.select2-ruangan').select2({
                placeholder: '-- Pilih Ruangan --',
                allowClear: false,
                width: '100%',
                theme: 'default'
            });

            // Handle ruangan selection change - load barang dynamically
            $('#ruangan_id').on('change', function () {
                const ruanganId = $(this).val();
                const barangContainer = $('#barang-container');
                const barangSelect = $('#barang_id');
                const unitContainer = $('#unit-container');
                const unitSelect = $('#barang_unit_id');

                // Reset unit dropdown
                unitContainer.hide();
                unitSelect.html('<option value="">Pilih Unit</option>');

                if (ruanganId) {
                    // Show loading state
                    barangSelect.html('<option value="">Loading...</option>');
                    barangContainer.show();

                    // Fetch barang via AJAX
                    $.ajax({
                        url: `{{ url('pengaduan/ruangan') }}/${ruanganId}/barang`,
                        method: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (barangs) {
                            barangSelect.html('<option value="">-- Pengaduan Ruangan (tanpa barang) --</option>');

                            if (barangs.length > 0) {
                                barangs.forEach(function (barang) {
                                    const kategoriLabel = barang.kategori ? ` (${barang.kategori})` : '';
                                    barangSelect.append(
                                        `<option value="${barang.id}">${barang.nama_barang}${kategoriLabel}</option>`
                                    );
                                });
                            } else {
                                barangSelect.html('<option value="">Tidak ada barang di ruangan ini</option>');
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error('Error loading barang:', error);
                            barangSelect.html('<option value="">Error loading barang</option>');
                        }
                    });
                } else {
                    // Hide barang container if no ruangan selected
                    barangContainer.hide();
                    barangSelect.html('<option value="">Pilih Barang</option>');
                }
            });

            // Handle barang selection change - load units dynamically
            $('#barang_id').on('change', function () {
                const barangId = $(this).val();
                const unitContainer = $('#unit-container');
                const unitSelect = $('#barang_unit_id');

                if (barangId) {
                    // Show loading state
                    unitSelect.html('<option value="">Loading...</option>');
                    unitContainer.show();

                    // Fetch units via AJAX
                    $.ajax({
                        url: `{{ url('pengaduan/barang') }}/${barangId}/units`,
                        method: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (units) {
                            unitSelect.html('<option value="">Pilih Unit</option>');

                            if (units.length > 0) {
                                units.forEach(function (unit) {
                                    const kondisiLabel = unit.kondisi ? ` (${unit.kondisi})` : '';
                                    unitSelect.append(
                                        `<option value="${unit.id}">${unit.kode_unit}${kondisiLabel}</option>`
                                    );
                                });
                            } else {
                                unitSelect.html('<option value="">Tidak ada unit tersedia</option>');
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error('Error loading units:', error);
                            unitSelect.html('<option value="">Error loading units</option>');
                        }
                    });
                } else {
                    // Hide unit container if no barang selected
                    unitContainer.hide();
                    unitSelect.html('<option value="">Pilih Unit</option>');
                }
            });
        });
    </script>

    <style>
        /* Custom Select2 styling to match design system */
        .select2-container--default .select2-selection--single {
            height: 48px !important;
            border: 1px solid #d1d5db !important;
            border-radius: 0.5rem !important;
            padding: 0.75rem 1rem !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 1.5rem !important;
            padding-left: 0 !important;
            color: #111827 !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 46px !important;
            right: 8px !important;
        }

        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #000 !important;
            box-shadow: 0 0 0 2px rgba(0, 0, 0, 0.1) !important;
        }

        .select2-dropdown {
            border: 1px solid #d1d5db !important;
            border-radius: 0.5rem !important;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #000 !important;
        }
    </style>
@endsection