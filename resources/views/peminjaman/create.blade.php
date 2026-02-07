@extends('layouts.main')

@section('title', 'Ajukan Peminjaman')

@push('styles')
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/dark.css">
    <style>
        .flatpickr-day.disabled {
            color: rgba(255, 255, 255, 0.1) !important;
            background: transparent !important;
            cursor: not-allowed !important;
        }

        .flatpickr-day.flatpickr-disabled,
        .flatpickr-day.flatpickr-disabled:hover {
            cursor: not-allowed !important;
            color: #ccc !important;
        }
    </style>
    /* Custom Select2 styling to match design system */
    .select2-container .select2-selection--single {
    height: 48px !important;
    border: 1px solid #d1d5db !important;
    border-radius: 0.5rem !important;
    padding: 0.25rem 0.5rem !important;
    background-color: white !important;
    display: flex !important;
    align-items: center !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: normal !important;
    color: #111827 !important;
    padding-left: 0.5rem !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 46px !important;
    right: 10px !important;
    }

    .select2-container--default.select2-container--focus .select2-selection--single {
    border-color: #000 !important;
    box-shadow: 0 0 0 2px rgba(0, 0, 0, 0.1) !important;
    }

    .select2-dropdown {
    border: 1px solid #d1d5db !important;
    border-radius: 0.5rem !important;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1) !important;
    z-index: 50;
    }

    .select2-container--default .select2-results__option--highlighted[aria-selected] {
    background-color: #000 !important;
    color: white !important;
    }

    .select2-container--default .select2-search--dropdown .select2-search__field {
    border: 1px solid #d1d5db !important;
    border-radius: 0.375rem !important;
    padding: 0.5rem !important;
    }

    .select2-container--default .select2-search--dropdown .select2-search__field:focus {
    border-color: #000 !important;
    outline: none !important;
    }
    </style>
@endpush

@section('content')
    <div class="max-w-2xl mx-auto">
        <a href="{{ route('peminjaman.index') }}"
            class="inline-flex items-center gap-2 text-gray-500 hover:text-gray-900 mb-6 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Riwayat
        </a>

        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Ajukan Peminjaman</h1>
            <p class="text-gray-500">Isi form untuk mengajukan peminjaman barang</p>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('peminjaman.store') }}"
            class="bg-white rounded-xl border border-gray-100 p-6 sm:p-8 shadow-sm">
            @csrf

            <input type="hidden" name="jumlah" value="1">

            <!-- Barang Selection -->
            <div class="mb-5">
                <label for="barang_id" class="block text-sm font-medium text-gray-700 mb-2">Barang yang Dipinjam <span
                        class="text-red-500">*</span></label>

                @if(isset($selectedUnit))
                    <!-- Unit Pre-selected Display -->
                    <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-bold text-gray-900">{{ $selectedBarang->nama_barang }}</span>
                            <span class="text-xs bg-black text-white px-2 py-1 rounded">
                                Unit: {{ $selectedUnit->kode_unit }}
                            </span>
                        </div>
                        <div class="text-sm text-gray-500">
                            Kondisi: {{ $selectedUnit->kondisi }}
                        </div>
                        <input type="hidden" name="barang_id" value="{{ $selectedBarang->id }}">
                        <input type="hidden" name="barang_unit_id" value="{{ $selectedUnit->id }}">
                    </div>
                    <div class="mt-2 text-xs text-blue-600 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Unit spesifik telah dipilih dari katalog.
                    </div>
                @else
                    <!-- Standard Selection -->
                    <select id="barang_id" name="barang_id" required class="select2-barang w-full">
                        <option value="">-- Ketik untuk mencari barang --</option>
                        @foreach($barangs as $barang)
                            <option value="{{ $barang->id }}" {{ (old('barang_id') == $barang->id || (isset($selectedBarang) && $selectedBarang->id == $barang->id)) ? 'selected' : '' }}>
                                {{ $barang->nama_barang }} (Stok: {{ $barang->jumlah_stok }})
                            </option>
                        @endforeach
                    </select>
                @endif

                @error('barang_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tanggal Pinjam -->
            <div class="mb-5">
                <label for="tgl_pinjam" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Pinjam <span
                        class="text-red-500">*</span></label>
                <div class="relative">
                    <input type="text" id="tgl_pinjam" name="tgl_pinjam"
                        value="{{ old('tgl_pinjam', $today ?? date('Y-m-d')) }}" required readonly
                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-black focus:border-transparent transition bg-white cursor-pointer"
                        placeholder="Pilih tanggal pinjam...">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
                <p class="text-gray-500 text-xs mt-1">Sabtu, Minggu, dan hari lampau tidak bisa dipilih</p>
                <p id="error_tgl_pinjam" class="text-red-500 text-xs mt-1 hidden"></p>
                @error('tgl_pinjam')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tanggal Kembali -->
            <div class="mb-5">
                <label for="tgl_kembali_rencana" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Kembali
                    (Rencana) <span class="text-red-500">*</span></label>
                <div class="relative">
                    <input type="text" id="tgl_kembali_rencana" name="tgl_kembali_rencana"
                        value="{{ old('tgl_kembali_rencana', $tomorrow ?? date('Y-m-d', strtotime('+1 day'))) }}" required
                        readonly
                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-black focus:border-transparent transition bg-white cursor-pointer"
                        placeholder="Pilih estimasi kembali...">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
                <p class="text-gray-500 text-xs mt-1">Minimal 1 hari, maksimal 7 hari</p>
                <p id="error_tgl_kembali" class="text-red-500 text-xs mt-1 hidden"></p>
                @error('tgl_kembali_rencana')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tujuan -->
            <div class="mb-5">
                <label for="tujuan_pinjam" class="block text-sm font-medium text-gray-700 mb-2">Tujuan Pinjam <span
                        class="text-red-500">*</span></label>
                <textarea id="tujuan_pinjam" name="tujuan_pinjam" rows="3" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-black focus:border-transparent transition"
                    placeholder="Jelaskan tujuan peminjaman">{{ old('tujuan_pinjam') }}</textarea>
                @error('tujuan_pinjam')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit -->
            <div class="flex flex-col sm:flex-row gap-3 pt-4">
                <button type="submit"
                    class="flex-1 py-3 px-6 bg-black text-white rounded-lg font-semibold hover:bg-gray-800 transition">
                    Ajukan Peminjaman
                </button>
                <a href="{{ route('peminjaman.index') }}"
                    class="py-3 px-6 border border-gray-300 text-gray-600 rounded-lg font-medium text-center hover:bg-gray-50 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <!-- jQuery (required for Select2) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
    <script>
        $(document).ready(function () {
            // Function to disable weekends
            const disableWeekends = function (date) {
                return (date.getDay() === 0 || date.getDay() === 6);
            };

            // Initialize Flatpickr for Tanggal Kembali
            const fpKembali = flatpickr("#tgl_kembali_rencana", {
                locale: "id",
                altInput: true,
                altFormat: "d F Y",
                dateFormat: "Y-m-d",
                minDate: "today",
                disable: [disableWeekends]
            });

            // Initialize Flatpickr for Tanggal Pinjam
            const fpPinjam = flatpickr("#tgl_pinjam", {
                locale: "id",
                altInput: true,
                altFormat: "d F Y",
                dateFormat: "Y-m-d",
                minDate: "today",
                disable: [disableWeekends],
                onChange: function (selectedDates, dateStr, instance) {
                    if (selectedDates.length > 0) {
                        const borrowDate = selectedDates[0];

                        // Min date kembali = tanggal pinjam + 1 hari (tidak boleh hari yang sama)
                        const minReturnDate = new Date(borrowDate);
                        minReturnDate.setDate(minReturnDate.getDate() + 1);

                        // Max date kembali = tanggal pinjam + 7 hari
                        const maxReturnDate = new Date(borrowDate);
                        maxReturnDate.setDate(maxReturnDate.getDate() + 7);

                        fpKembali.set('minDate', minReturnDate);
                        fpKembali.set('maxDate', maxReturnDate);

                        if (fpKembali.selectedDates.length > 0) {
                            const currentReturn = fpKembali.selectedDates[0];
                            if (currentReturn < minReturnDate || currentReturn > maxReturnDate) {
                                fpKembali.clear();
                            }
                        }
                    }
                }
            });

            // Initialize Select2 for Barang dropdown if it exists
            if ($('.select2-barang').length > 0) {
                $('.select2-barang').select2({
                    placeholder: '-- Ketik untuk mencari barang --',
                    allowClear: true,
                    width: '100%',
                    language: {
                        noResults: function () { return "Barang tidak ditemukan"; },
                        searching: function () { return "Mencari..."; }
                    }
                });
            }

            // Date Validation Utilities
            const isWeekend = function (date) {
                const day = date.getDay();
                return (day === 0 || day === 6); // Sunday = 0, Saturday = 6
            };

            // DOM Elements
            const errorPinjam = document.getElementById('error_tgl_pinjam');
            const errorKembali = document.getElementById('error_tgl_kembali');
            const form = document.querySelector('form');

            // Show error function
            const showError = function (element, message) {
                element.textContent = message;
                element.classList.remove('hidden');
            };

            const hideError = function (element) {
                element.textContent = '';
                element.classList.add('hidden');
            };

            // Form submission validation
            form.addEventListener('submit', function (e) {
                let hasError = false;
                hideError(errorPinjam);
                hideError(errorKembali);

                const tglPinjamVal = document.getElementById('tgl_pinjam').value;
                const tglKembaliVal = document.getElementById('tgl_kembali_rencana').value;

                // Validate borrow date
                if (!tglPinjamVal) {
                    showError(errorPinjam, 'Tanggal pinjam wajib diisi.');
                    hasError = true;
                } else {
                    const d = new Date(tglPinjamVal);
                    if (isWeekend(d)) {
                        showError(errorPinjam, 'Tanggal pinjam tidak boleh jatuh pada hari Sabtu atau Minggu.');
                        hasError = true;
                    }
                }

                // Validate return date
                if (!tglKembaliVal) {
                    showError(errorKembali, 'Tanggal kembali wajib diisi.');
                    hasError = true;
                } else {
                    const d = new Date(tglKembaliVal);
                    if (isWeekend(d)) {
                        showError(errorKembali, 'Tanggal kembali tidak boleh jatuh pada hari Sabtu atau Minggu.');
                        hasError = true;
                    }
                }

                // Validate return date is after borrow date
                if (tglPinjamVal && tglKembaliVal) {
                    if (tglKembaliVal < tglPinjamVal) {
                        showError(errorKembali, 'Tanggal kembali tidak boleh sebelum tanggal pinjam.');
                        hasError = true;
                    }

                    // Validate max 7 days
                    const borrowDate = new Date(tglPinjamVal);
                    const returnDate = new Date(tglKembaliVal);
                    const diffTime = returnDate - borrowDate;
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                    if (diffDays > 7) {
                        showError(errorKembali, 'Durasi peminjaman maksimal 7 hari.');
                        hasError = true;
                    }
                }

                if (hasError) {
                    e.preventDefault();
                    // Scroll to first error
                    const firstError = document.querySelector('.text-red-500:not(.hidden)');
                    if (firstError) {
                        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
            });
        });
    </script>
@endpush