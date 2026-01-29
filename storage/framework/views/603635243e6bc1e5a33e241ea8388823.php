

<?php $__env->startSection('title', 'Katalog Barang'); ?>

<?php $__env->startPush('styles'); ?>
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Custom Select2 styling for filter */
        .select2-container .select2-selection--single {
            height: 42px !important;
            border: 1px solid #e5e7eb !important;
            border-radius: 0.5rem !important;
            background-color: #f9fafb !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 40px !important;
            color: #111827 !important;
            padding-left: 0.75rem !important;
            font-size: 0.875rem !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px !important;
            right: 8px !important;
        }

        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #000 !important;
            box-shadow: 0 0 0 2px rgba(0, 0, 0, 0.1) !important;
        }

        .select2-dropdown {
            border: 1px solid #e5e7eb !important;
            border-radius: 0.5rem !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1) !important;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #000 !important;
            color: white !important;
        }

        .select2-container--default .select2-search--dropdown .select2-search__field {
            border: 1px solid #e5e7eb !important;
            border-radius: 0.375rem !important;
            padding: 0.5rem !important;
        }

        .select2-container--default .select2-search--dropdown .select2-search__field:focus {
            border-color: #000 !important;
            outline: none !important;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <!-- Header Section -->
    <div class="bg-black rounded-2xl p-6 sm:p-10 mb-8 text-white relative overflow-hidden">
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
            <div>
                <h1 class="text-3xl sm:text-4xl font-bold mb-2">Katalog Barang</h1>
                <p class="text-gray-400 max-w-xl">
                    Temukan berbagai sarana dan prasarana yang tersedia untuk menunjang kegiatan Anda.
                    Gunakan fitur pencarian untuk menemukan barang dengan cepat.
                </p>
            </div>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="sticky top-20 z-30 bg-white/80 backdrop-blur-md border border-gray-100 rounded-xl shadow-sm p-4 mb-8">
        <form method="GET" action="<?php echo e(route('katalog.index')); ?>" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Filter Kategori -->
            <div>
                <select name="kategori_id" id="filter-kategori" class="select2-filter w-full">
                    <option value="">Semua Kategori</option>
                    <?php $__currentLoopData = $kategoris; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kategori): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($kategori->id); ?>" <?php echo e(request('kategori_id') == $kategori->id ? 'selected' : ''); ?>>
                            <?php echo e($kategori->nama_kategori); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <!-- Filter Ruangan -->
            <div>
                <select name="ruangan_id" id="filter-ruangan" class="select2-filter w-full">
                    <option value="">Semua Ruangan</option>
                    <?php $__currentLoopData = $ruangans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ruangan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($ruangan->id); ?>" <?php echo e(request('ruangan_id') == $ruangan->id ? 'selected' : ''); ?>>
                            <?php echo e($ruangan->nama_ruangan); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <!-- Filter Jenis Aset -->
            <div>
                <select name="jenis_aset"
                    class="w-full rounded-lg border-gray-200 bg-gray-50 focus:border-black focus:ring-black transition py-2.5 text-sm"
                    onchange="this.form.submit()">
                    <option value="">Semua Aset</option>
                    <option value="tik" <?php echo e(request('jenis_aset') == 'tik' ? 'selected' : ''); ?>>Aset TIK</option>
                    <option value="non_tik" <?php echo e(request('jenis_aset') == 'non_tik' ? 'selected' : ''); ?>>Non-TIK</option>
                </select>
            </div>

            <!-- Search -->
            <div class="flex gap-2">
                <div class="relative flex-1">
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari nama barang..."
                        class="w-full rounded-lg border-gray-200 bg-gray-50 focus:border-black focus:ring-black pl-10 py-2.5 text-sm transition">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
        </form>
    </div>

    <!-- Grid Barang -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <?php $__empty_1 = true; $__currentLoopData = $barangs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $barang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div
                class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition duration-300 group">
                <div
                    class="h-56 bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center relative overflow-hidden group-hover:bg-gray-100 transition-colors">
                    <!-- Image or Default Icon -->
                    <?php if($barang->gambar): ?>
                        <img src="<?php echo e(Str::startsWith($barang->gambar, 'http') ? $barang->gambar : asset('storage/' . $barang->gambar)); ?>"
                            alt="<?php echo e($barang->nama_barang); ?>"
                            class="w-full h-full object-cover transform group-hover:scale-110 transition duration-500">
                    <?php else: ?>
                        <div class="transform group-hover:scale-110 transition duration-500">
                            <svg class="w-20 h-20 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    <?php endif; ?>

                    <!-- Badges -->
                    <div class="absolute top-4 right-4 flex flex-col gap-2 items-end">
                        <?php if($barang->jenis_aset == 'tik'): ?>
                            <span class="bg-black text-white text-[10px] font-bold px-2.5 py-1 rounded-full shadow-sm">
                                TIK
                            </span>
                        <?php endif; ?>
                        <span
                            class="bg-white/90 backdrop-blur-sm text-gray-700 text-[10px] font-bold px-2.5 py-1 rounded-full shadow-sm border border-gray-100">
                            <?php echo e($barang->kategori->nama_kategori); ?>

                        </span>
                    </div>

                    <!-- Quick Info Overlay -->
                    <div
                        class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300">
                        <?php if($barang->jumlah_stok > 0): ?>
                            <a href="<?php echo e(route('katalog.show', $barang->id)); ?>"
                                class="bg-white text-black px-6 py-2.5 rounded-full font-bold transform translate-y-4 group-hover:translate-y-0 transition duration-300 hover:scale-105">
                                Lihat Detail
                            </a>
                        <?php else: ?>
                            <span
                                class="bg-red-500 text-white px-6 py-2 rounded-full font-bold text-sm transform translate-y-4 group-hover:translate-y-0 transition duration-300">
                                Stok Habis
                            </span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="p-5">
                    <div class="flex justify-between items-start mb-2">
                        <h3
                            class="font-bold text-gray-900 text-lg leading-tight line-clamp-2 min-h-[50px] group-hover:text-blue-600 transition">
                            <?php echo e($barang->nama_barang); ?>

                        </h3>
                    </div>

                    <div class="w-full h-px bg-gray-50 my-3"></div>

                    <div class="flex items-center justify-between">
                        <div class="flex flex-col">
                            <span class="text-xs text-gray-400 font-medium">Ketersediaan</span>
                            <span class="font-bold <?php echo e($barang->jumlah_stok > 0 ? 'text-green-600' : 'text-red-500'); ?>">
                                <?php echo e($barang->jumlah_stok); ?> Unit
                            </span>
                        </div>
                        <?php if($barang->jumlah_stok > 0): ?>
                            <div
                                class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 group-hover:bg-black group-hover:text-white transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div
                class="col-span-full flex flex-col items-center justify-center py-16 text-center bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Tidak ada barang ditemukan</h3>
                <p class="text-gray-500 max-w-sm mx-auto">
                    Coba gunakan kata kunci lain atau ubah filter kategori untuk menemukan barang yang Anda cari.
                </p>
                <a href="<?php echo e(route('katalog.index')); ?>" class="mt-6 text-black font-semibold hover:underline">
                    Reset Filter
                </a>
            </div>
        <?php endif; ?>
    </div>

    <div class="mt-8 flex justify-center">
        <?php echo e($barangs->withQueryString()->links()); ?>

    </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <!-- jQuery (required for Select2) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            // Initialize Select2 for filter dropdowns
            $('.select2-filter').select2({
                allowClear: true,
                width: '100%',
                language: {
                    noResults: function () {
                        return "Tidak ditemukan";
                    },
                    searching: function () {
                        return "Mencari...";
                    }
                }
            });

            // Auto-submit form when Select2 changes
            $('.select2-filter').on('change', function () {
                $(this).closest('form').submit();
            });
        });
    </script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\PROJECT-UKK\resources\views/main/katalog/index.blade.php ENDPATH**/ ?>