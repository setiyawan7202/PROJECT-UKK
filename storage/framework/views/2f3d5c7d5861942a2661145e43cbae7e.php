

<?php $__env->startSection('title', 'Dashboard - SIAPRAS'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Welcome Banner -->
    <div class="bg-black text-white rounded-xl sm:rounded-2xl p-4 sm:p-8 mb-4 sm:mb-8 fade-in">
        <h1 class="text-lg sm:text-2xl md:text-3xl font-bold mb-1 sm:mb-2">
            Selamat Datang,
            <?php echo e(Auth::user()->nama_lengkap); ?>!
        </h1>
        <p class="text-gray-300 text-sm sm:text-base">
            Kelola peminjaman sarana dan prasarana sekolah dengan mudah.
        </p>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-3 gap-2 sm:gap-4 lg:gap-6 mb-4 sm:mb-8">
        <!-- Stat 1 -->
        <div class="bg-white rounded-xl sm:rounded-2xl border border-gray-100 p-3 sm:p-6 card-hover fade-in">
            <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4">
                <div class="w-8 h-8 sm:w-12 sm:h-12 bg-gray-100 rounded-lg sm:rounded-xl flex items-center justify-center">
                    <svg class="w-4 h-4 sm:w-6 sm:h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
                <div>
                    <p class="text-lg sm:text-2xl font-bold text-gray-900"><?php echo e($stats['active']); ?></p>
                    <p class="text-[10px] sm:text-sm text-gray-500">Aktif</p>
                </div>
            </div>
        </div>

        <!-- Stat 2 -->
        <div class="bg-white rounded-xl sm:rounded-2xl border border-gray-100 p-3 sm:p-6 card-hover fade-in">
            <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4">
                <div class="w-8 h-8 sm:w-12 sm:h-12 bg-gray-100 rounded-lg sm:rounded-xl flex items-center justify-center">
                    <svg class="w-4 h-4 sm:w-6 sm:h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-lg sm:text-2xl font-bold text-gray-900"><?php echo e($stats['pending']); ?></p>
                    <p class="text-[10px] sm:text-sm text-gray-500">Pending</p>
                </div>
            </div>
        </div>

        <!-- Stat 3 -->
        <div class="bg-white rounded-xl sm:rounded-2xl border border-gray-100 p-3 sm:p-6 card-hover fade-in">
            <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4">
                <div class="w-8 h-8 sm:w-12 sm:h-12 bg-gray-100 rounded-lg sm:rounded-xl flex items-center justify-center">
                    <svg class="w-4 h-4 sm:w-6 sm:h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div>
                    <p class="text-lg sm:text-2xl font-bold text-gray-900"><?php echo e($stats['completed']); ?></p>
                    <p class="text-[10px] sm:text-sm text-gray-500">Selesai</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <h2 class="text-base sm:text-lg font-bold text-gray-900 mb-3 sm:mb-4">Menu Cepat</h2>
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-2 sm:gap-4">
        <!-- Action 1: Katalog Barang -->
        <a href="<?php echo e(route('katalog.index')); ?>"
            class="bg-white rounded-xl sm:rounded-2xl border border-gray-100 p-4 sm:p-6 text-center card-hover fade-in">
            <div
                class="w-10 h-10 sm:w-14 sm:h-14 bg-black rounded-lg sm:rounded-xl flex items-center justify-center mx-auto mb-2 sm:mb-4">
                <svg class="w-5 h-5 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
            </div>
            <h3 class="font-semibold text-gray-900 text-sm sm:text-base mb-0.5 sm:mb-1">Katalog</h3>
            <p class="text-[10px] sm:text-sm text-gray-500 hidden sm:block">Lihat & pinjam barang</p>
        </a>

        <!-- Action 2: Riwayat -->
        <a href="<?php echo e(route('riwayat.index')); ?>"
            class="bg-white rounded-xl sm:rounded-2xl border border-gray-100 p-4 sm:p-6 text-center card-hover fade-in">
            <div
                class="w-10 h-10 sm:w-14 sm:h-14 bg-gray-100 rounded-lg sm:rounded-xl flex items-center justify-center mx-auto mb-2 sm:mb-4">
                <svg class="w-5 h-5 sm:w-7 sm:h-7 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                </svg>
            </div>
            <h3 class="font-semibold text-gray-900 text-sm sm:text-base mb-0.5 sm:mb-1">Riwayat</h3>
            <p class="text-[10px] sm:text-sm text-gray-500 hidden sm:block">Lihat riwayat peminjaman</p>
        </a>

        <!-- Action 3: Peminjaman Aktif -->
        <a href="<?php echo e(route('peminjaman.index')); ?>"
            class="bg-white rounded-xl sm:rounded-2xl border border-gray-100 p-4 sm:p-6 text-center card-hover fade-in">
            <div
                class="w-10 h-10 sm:w-14 sm:h-14 bg-gray-100 rounded-lg sm:rounded-xl flex items-center justify-center mx-auto mb-2 sm:mb-4">
                <svg class="w-5 h-5 sm:w-7 sm:h-7 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
            </div>
            <h3 class="font-semibold text-gray-900 text-sm sm:text-base mb-0.5 sm:mb-1">Peminjaman</h3>
            <p class="text-[10px] sm:text-sm text-gray-500 hidden sm:block">Status peminjaman</p>
        </a>

        <!-- Action 4: Pengaduan -->
        <a href="<?php echo e(route('pengaduan.index')); ?>"
            class="bg-white rounded-xl sm:rounded-2xl border border-gray-100 p-4 sm:p-6 text-center card-hover fade-in">
            <div
                class="w-10 h-10 sm:w-14 sm:h-14 bg-gray-100 rounded-lg sm:rounded-xl flex items-center justify-center mx-auto mb-2 sm:mb-4">
                <svg class="w-5 h-5 sm:w-7 sm:h-7 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <h3 class="font-semibold text-gray-900 text-sm sm:text-base mb-0.5 sm:mb-1">Pengaduan</h3>
            <p class="text-[10px] sm:text-sm text-gray-500 hidden sm:block">Lapor kerusakan sarpras</p>
        </a>

        <!-- Action 5: Profil -->
        <a href="<?php echo e(route('profil.index')); ?>"
            class="bg-white rounded-xl sm:rounded-2xl border border-gray-100 p-4 sm:p-6 text-center card-hover fade-in">
            <div
                class="w-10 h-10 sm:w-14 sm:h-14 bg-gray-100 rounded-lg sm:rounded-xl flex items-center justify-center mx-auto mb-2 sm:mb-4">
                <svg class="w-5 h-5 sm:w-7 sm:h-7 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <h3 class="font-semibold text-gray-900 text-sm sm:text-base mb-0.5 sm:mb-1">Profil</h3>
            <p class="text-[10px] sm:text-sm text-gray-500 hidden sm:block">Pengaturan akun</p>
        </a>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\PROJECT-UKK\resources\views/main/index.blade.php ENDPATH**/ ?>