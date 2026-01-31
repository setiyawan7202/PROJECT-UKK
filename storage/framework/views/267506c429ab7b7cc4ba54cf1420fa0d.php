

<?php $__env->startSection('title', 'Edit User'); ?>

<?php $__env->startSection('content'); ?>
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <a href="<?php echo e(route('admin.users.index')); ?>"
                class="hidden lg:inline-flex items-center gap-2 text-gray-500 hover:text-gray-900 text-sm mb-4 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali ke Daftar User
            </a>
            <h1 class="text-xl lg:text-2xl font-bold text-gray-900">Edit User</h1>
            <p class="text-sm text-gray-500">Perbarui data user</p>
        </div>

        <!-- Error Messages -->
        <?php if($errors->any()): ?>
            <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">
                <ul class="list-disc list-inside space-y-1">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Form -->
        <form method="POST" action="<?php echo e(route('admin.users.update', $user->id)); ?>"
            class="bg-white rounded-xl lg:rounded-2xl border border-gray-100 p-6">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <!-- Email -->
            <div class="mb-5">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email <span
                        class="text-red-500">*</span></label>
                <input type="email" id="email" name="email" value="<?php echo e(old('email', $user->email)); ?>" required
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-1 focus:ring-black transition"
                    placeholder="Masukkan email">
            </div>

            <!-- Password -->
            <div class="mb-5">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                <div class="relative">
                    <input type="password" id="password" name="password"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-1 focus:ring-black transition"
                        placeholder="Kosongkan jika tidak ingin mengubah" minlength="8">
                </div>
            </div>

            <!-- Nama Lengkap -->
            <!-- Nama Lengkap (Displayed from relationship, read-only here or updated via relationship? For now, we update users.nama_lengkap... wait, we dropped it!) -->
            <div class="mb-5">
                <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap <span
                        class="text-red-500">*</span></label>
                <input type="text" id="nama_lengkap" name="nama_lengkap" required
                    value="<?php echo e(old('nama_lengkap', ($user->siswa ? $user->siswa->username : ($user->guru ? $user->guru->username : '')))); ?>"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-1 focus:ring-black transition"
                    placeholder="Nama lengkap">
            </div>

            <!-- Role -->
            <div class="mb-5">
                <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Role <span
                        class="text-red-500">*</span></label>
                <select id="role" name="role" required
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-1 focus:ring-black transition bg-white">
                    <option value="admin" <?php echo e(old('role', $user->role) === 'admin' ? 'selected' : ''); ?>>Admin</option>
                    <option value="petugas" <?php echo e(old('role', $user->role) === 'petugas' ? 'selected' : ''); ?>>Petugas</option>
                    <option value="pengguna" <?php echo e(old('role', $user->role) === 'pengguna' ? 'selected' : ''); ?>>Pengguna</option>
                </select>
            </div>

            <!-- Status -->
            <div class="mb-5">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select id="status" name="status"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-1 focus:ring-black transition bg-white"
                    onchange="toggleKelasField()">
                    <option value="">Tidak ada</option>
                    <option value="siswa" <?php echo e(old('status', $user->status) === 'siswa' ? 'selected' : ''); ?>>Siswa</option>
                    <option value="guru" <?php echo e(old('status', $user->status) === 'guru' ? 'selected' : ''); ?>>Guru</option>
                </select>
            </div>

            <!-- No HP (Optional) -->
            <div class="mb-5" id="no_hp-field"
                style="<?php echo e(in_array(old('status', $user->status), ['siswa', 'guru']) ? '' : 'display: none;'); ?>">
                <label for="no_hp" class="block text-sm font-medium text-gray-700 mb-2">No. HP <span
                        class="text-gray-400 font-normal">(Opsional)</span></label>
                <div class="relative">
                    <input type="text" id="no_hp" name="no_hp"
                        value="<?php echo e(old('no_hp', $user->siswa ? $user->siswa->no_hp : ($user->guru ? $user->guru->no_hp : ''))); ?>"
                        class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-1 focus:ring-black transition"
                        placeholder="Contoh: 08123456789">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-medium">
                        +62
                    </div>
                </div>
                <p class="mt-1 text-xs text-gray-500">Nomor WhatsApp aktif direkomendasikan.</p>
            </div>

            <!-- NISN (only shown when status = siswa) -->
            <div class="mb-5" id="nisn-field"
                style="<?php echo e(old('status', $user->status) === 'siswa' ? '' : 'display: none;'); ?>">
                <label for="nisn" class="block text-sm font-medium text-gray-700 mb-2">NISN <span
                        class="text-red-500">*</span></label>
                <input type="number" id="nisn" name="nisn" value="<?php echo e(old('nisn', $user->siswa?->nisn)); ?>"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-1 focus:ring-black transition"
                    placeholder="Masukkan NISN">
            </div>

            <!-- NIP (only shown when status = guru) -->
            <div class="mb-5" id="nip-field" style="<?php echo e(old('status', $user->status) === 'guru' ? '' : 'display: none;'); ?>">
                <label for="nip" class="block text-sm font-medium text-gray-700 mb-2">NIP <span
                        class="text-red-500">*</span></label>
                <input type="number" id="nip" name="nip" value="<?php echo e(old('nip', $user->guru?->nip)); ?>"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-1 focus:ring-black transition"
                    placeholder="Masukkan NIP">
            </div>

            <!-- Kelas (only shown when status = siswa) -->
            <div class="mb-5" id="kelas-field"
                style="<?php echo e(old('status', $user->status) === 'siswa' ? '' : 'display: none;'); ?>">
                <label for="kelas_id" class="block text-sm font-medium text-gray-700 mb-2">Kelas</label>
                <select id="kelas_id" name="kelas_id" class="searchable-select w-full">
                    <option value="">Pilih Kelas</option>
                    <?php $__currentLoopData = $kelasList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kelas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($kelas->id); ?>" <?php echo e(old('kelas_id', $user->kelas_id) == $kelas->id ? 'selected' : ''); ?>>
                            <?php echo e($kelas->nama_kelas); ?> (<?php echo e($kelas->jurusan); ?>)
                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <!-- Submit -->
            <div class="flex flex-col sm:flex-row gap-3">
                <button type="submit"
                    class="flex-1 py-3 px-6 bg-black text-white rounded-xl font-semibold hover:bg-gray-800 transition">
                    Simpan Perubahan
                </button>
                <a href="<?php echo e(route('admin.users.index')); ?>"
                    class="py-3 px-6 border border-gray-200 text-gray-600 rounded-xl font-medium text-center hover:bg-gray-50 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>     function togglePasswordVisibility(id) {
            const input = document.getElementById(id); const eyeIcon = document.getElementById('eye-icon-' + id); const eyeOffIcon = document.getElementById('eye-off-icon-' + id);
            if (input.type === 'password') { input.type = 'text'; eyeIcon.classList.add('hidden'); eyeOffIcon.classList.remove('hidden'); } else { input.type = 'password'; eyeIcon.classList.remove('hidden'); eyeOffIcon.classList.add('hidden'); }
        }
        function toggleKelasField() {
            const status = document.getElementById('status').value; const kelasField = document.getElementById('kelas-field');
            if (status === 'siswa') {
                kelasField.style.display = 'block';
                document.getElementById('nisn-field').style.display = 'block';
                document.getElementById('nip-field').style.display = 'none';
                document.getElementById('no_hp-field').style.display = 'block';
            } else if (status === 'guru') {
                kelasField.style.display = 'none';
                document.getElementById('kelas_id').value = '';
                document.getElementById('nisn-field').style.display = 'none';
                document.getElementById('nip-field').style.display = 'block';
                document.getElementById('no_hp-field').style.display = 'block';
            } else {
                kelasField.style.display = 'none';
                document.getElementById('kelas_id').value = '';
                document.getElementById('nisn-field').style.display = 'none';
                document.getElementById('nip-field').style.display = 'none';
                document.getElementById('no_hp-field').style.display = 'none';
            }
        }
    </script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\PROJECT-UKK\resources\views/admin/users/edit.blade.php ENDPATH**/ ?>