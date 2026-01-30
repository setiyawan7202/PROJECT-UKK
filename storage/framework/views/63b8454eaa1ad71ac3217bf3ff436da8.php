

<?php $__env->startSection('title', 'Edit Ruangan'); ?>

<?php $__env->startSection('content'); ?>
    <div class="max-w-xl mx-auto">
        <div class="mb-6">
            <a href="<?php echo e(route('admin.ruangan.index')); ?>"
                class="inline-flex items-center gap-2 text-gray-500 hover:text-gray-900 text-sm mb-4 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Edit Ruangan</h1>
            <p class="text-sm text-gray-500">Perbarui data ruangan</p>
        </div>

        <?php if($errors->any()): ?>
            <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">
                <ul class="list-disc list-inside">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="bg-white border border-gray-100 rounded-xl p-6 lg:p-8">
            <form action="<?php echo e(route('admin.ruangan.update', $ruangan->id)); ?>" method="POST" class="space-y-6">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div>
                    <label for="kode_ruangan" class="block text-sm font-medium text-gray-700 mb-2">Kode Ruangan <span
                            class="text-red-500">*</span></label>
                    <input type="text" id="kode_ruangan" name="kode_ruangan"
                        value="<?php echo e(old('kode_ruangan', $ruangan->kode_ruangan)); ?>"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-1 focus:ring-black"
                        placeholder="Masukkan kode ruangan">
                </div>

                <div>
                    <label for="nama_ruangan" class="block text-sm font-medium text-gray-700 mb-2">Nama Ruangan <span
                            class="text-red-500">*</span></label>
                    <input type="text" id="nama_ruangan" name="nama_ruangan"
                        value="<?php echo e(old('nama_ruangan', $ruangan->nama_ruangan)); ?>"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-1 focus:ring-black"
                        placeholder="Masukkan nama ruangan">
                </div>

                <div>
                    <label for="lokasi" class="block text-sm font-medium text-gray-700 mb-2">Lokasi <span
                            class="text-red-500">*</span></label>
                    <input type="text" id="lokasi" name="lokasi" value="<?php echo e(old('lokasi', $ruangan->lokasi)); ?>"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-1 focus:ring-black"
                        placeholder="Masukkan lokasi ruangan">
                </div>

                <div>
                    <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                    <textarea id="keterangan" name="keterangan" rows="3"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-1 focus:ring-black"><?php echo e(old('keterangan', $ruangan->keterangan)); ?></textarea>
                </div>

                <div class="flex items-center gap-4 mt-8">
                    <button type="submit"
                        class="bg-black text-white px-6 py-3 rounded-xl font-medium hover:bg-gray-800 transition">Simpan
                        Perubahan</button>
                    <a href="<?php echo e(route('admin.ruangan.index')); ?>"
                        class="text-gray-600 font-medium hover:text-gray-900">Batal</a>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\PROJECT-UKK\resources\views/admin/ruangan/edit.blade.php ENDPATH**/ ?>