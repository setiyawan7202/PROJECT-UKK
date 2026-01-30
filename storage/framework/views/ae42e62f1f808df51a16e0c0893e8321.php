

<?php $__env->startSection('title', 'Manajemen Peminjaman'); ?>

<?php $__env->startSection('content'); ?>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Manajemen Peminjaman</h1>
        <p class="text-sm text-gray-500">Kelola persetujuan dan status peminjaman.</p>
    </div>

    <?php if(session('success')): ?>
        <div class="mb-4 p-4 bg-gray-50 border border-gray-200 text-gray-800 rounded-xl text-sm">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="mb-4 p-4 bg-white border border-gray-200 text-red-600 rounded-xl text-sm">
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    <!-- Filters -->
    <div class="bg-white p-4 rounded-xl border border-gray-100 mb-6">
        <form method="GET" action="<?php echo e(route('admin.peminjaman.index')); ?>" class="flex gap-4">
            <select name="status" class="px-4 py-2 border rounded-lg text-sm focus:ring-black focus:border-black"
                onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="pending" <?php echo e(request('status') == 'pending' ? 'selected' : ''); ?>>Pending</option>
                <option value="approved" <?php echo e(request('status') == 'approved' ? 'selected' : ''); ?>>Approved</option>
                <option value="active" <?php echo e(request('status') == 'active' ? 'selected' : ''); ?>>Active</option>
                <option value="completed" <?php echo e(request('status') == 'completed' ? 'selected' : ''); ?>>Completed</option>
                <option value="rejected" <?php echo e(request('status') == 'rejected' ? 'selected' : ''); ?>>Rejected</option>
            </select>
        </form>
    </div>

    <div class="bg-white border border-gray-100 rounded-xl overflow-hidden">
        <div class="overflow-x-auto w-full">
            <table class="w-full min-w-[1000px] text-left text-sm whitespace-nowrap">
                <thead class="bg-gray-50 text-gray-500">
                    <tr>
                        <th class="px-6 py-3 font-semibold">Kode</th>
                        <th class="px-6 py-3 font-semibold">Peminjam</th>
                        <th class="px-6 py-3 font-semibold">Barang</th>
                        <th class="px-6 py-3 font-semibold">Tgl Pinjam</th>
                        <th class="px-6 py-3 font-semibold">Tujuan</th>
                        <th class="px-6 py-3 font-semibold">Status</th>
                        <th class="px-6 py-3 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php $__empty_1 = true; $__currentLoopData = $peminjaman; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <span class="font-mono text-xs font-bold text-gray-900"><?php echo e($item->kode ?? '-'); ?></span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900"><?php echo e($item->user->nama_lengkap); ?></div>
                                <div class="text-xs text-gray-500">
                                    <?php echo e($item->user->siswa->kelas->nama_kelas ?? ucfirst($item->user->role)); ?>

                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium"><?php echo e($item->barang->nama_barang); ?></div>
                                <div class="text-xs text-gray-500">
                                    Unit: <?php echo e($item->barangUnit->kode_unit ?? '(Belum dialokasikan)'); ?>

                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div><?php echo e($item->tgl_pinjam->format('d/m/Y')); ?></div>
                                <div class="text-xs text-gray-400">s/d <?php echo e($item->tgl_kembali_rencana->format('d/m/Y')); ?></div>
                            </td>
                            <td class="px-6 py-4 text-gray-500 max-w-xs truncate" title="<?php echo e($item->tujuan_pinjam); ?>">
                                <?php echo e(Str::limit($item->tujuan_pinjam, 30)); ?>

                            </td>
                            <td class="px-6 py-4">
                                <?php
                                    $colors = [
                                        'pending' => 'bg-gray-100 text-gray-600 border border-gray-200',
                                        'approved' => 'bg-black text-white border border-black',
                                        'active' => 'bg-gray-800 text-white border border-gray-800',
                                        'completed' => 'bg-white text-gray-800 border border-gray-300',
                                        'rejected' => 'bg-white text-red-600 border border-red-200',
                                        'overdue' => 'bg-red-50 text-red-700 border border-red-200',
                                    ];
                                ?>
                                <span
                                    class="px-2 py-1 rounded-md text-xs font-semibold <?php echo e($colors[$item->status] ?? 'bg-gray-100'); ?>">
                                    <?php echo e(ucfirst($item->status)); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <?php if($item->status == 'pending'): ?>
                                    <div class="flex justify-end gap-2">
                                        <a href="<?php echo e(route('admin.peminjaman.show', $item->id)); ?>"
                                            class="bg-white border border-gray-200 text-gray-700 px-3 py-1 rounded-lg text-xs hover:bg-gray-50 font-medium">
                                            Detail
                                        </a>
                                        <button
                                            onclick="openApproveModal('<?php echo e($item->id); ?>', '<?php echo e($item->barang->nama_barang); ?>', <?php echo e($item->barang->units->where('status', 'aktif')->pluck('kode_unit', 'id')); ?>)"
                                            class="bg-black text-white px-3 py-1 rounded-lg text-xs hover:bg-gray-800">
                                            Approve
                                        </button>
                                        <button onclick="openRejectModal('<?php echo e($item->id); ?>')"
                                            class="bg-white text-gray-700 border border-gray-300 px-3 py-1 rounded-lg text-xs hover:bg-gray-50">
                                            Reject
                                        </button>
                                    </div>
                                <?php elseif($item->status == 'approved'): ?>
                                    <div class="flex justify-end gap-2">
                                        <form action="<?php echo e(route('admin.peminjaman.activate', $item->id)); ?>" method="POST"
                                            onsubmit="return confirm('Konfirmasi pengambilan barang?')">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit"
                                                class="bg-black text-white px-3 py-1 rounded-lg text-xs hover:bg-gray-800">
                                                Ambil Barang
                                            </button>
                                        </form>
                                        <a href="<?php echo e(route('admin.peminjaman.show', $item->id)); ?>"
                                            class="bg-white border border-gray-200 text-gray-700 px-3 py-1 rounded-lg text-xs hover:bg-gray-50 font-medium">
                                            Detail
                                        </a>
                                        <a href="<?php echo e(route('admin.peminjaman.bukti', $item->id)); ?>" target="_blank"
                                            class="bg-white border border-gray-200 text-gray-700 px-3 py-1 rounded-lg text-xs hover:bg-gray-50">
                                            Cetak Bukti
                                        </a>
                                    </div>
                                <?php elseif($item->status == 'active'): ?>
                                    <a href="<?php echo e(route('admin.peminjaman.return', $item->id)); ?>"
                                        class="bg-black text-white px-3 py-1 rounded-lg text-xs hover:bg-gray-800">
                                        Kembalikan
                                    </a>
                                    <a href="<?php echo e(route('admin.peminjaman.show', $item->id)); ?>"
                                        class="bg-white border border-gray-200 text-gray-700 px-3 py-1 rounded-lg text-xs hover:bg-gray-50 font-medium">
                                        Detail
                                    </a>
                                    <a href="<?php echo e(route('admin.peminjaman.bukti', $item->id)); ?>" target="_blank"
                                        class="bg-white border border-gray-200 text-gray-700 px-3 py-1 rounded-lg text-xs hover:bg-gray-50">
                                        Cetak Bukti
                                    </a>
                                <?php else: ?>
                                    <div class="flex justify-end gap-2">
                                        <a href="<?php echo e(route('admin.peminjaman.show', $item->id)); ?>"
                                            class="bg-white border border-gray-200 text-gray-700 px-3 py-1 rounded-lg text-xs hover:bg-gray-50 font-medium">
                                            Detail
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">Tidak ada data peminjaman.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100">
            <?php echo e($peminjaman->links()); ?>

        </div>
    </div>

    <!-- Modal Approve -->
    <div id="approveModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white rounded-xl p-6 w-full max-w-md">
            <h3 class="text-lg font-bold mb-4">Setujui Peminjaman</h3>
            <p class="text-sm text-gray-600 mb-4" id="approveItemName"></p>

            <form id="approveForm" method="POST" action="">
                <?php echo csrf_field(); ?>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Pilih Unit Barang</label>
                    <select name="barang_unit_id" id="unitSelect" class="w-full border rounded-lg p-2" required>
                        <!-- Populated via JS -->
                    </select>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeApproveModal()"
                        class="px-4 py-2 text-gray-600 hover:text-gray-900">Batal</button>
                    <button type="submit"
                        class="bg-black text-white px-4 py-2 rounded-lg hover:bg-gray-800">Setujui</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Reject -->
    <div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white rounded-xl p-6 w-full max-w-md">
            <h3 class="text-lg font-bold mb-4">Tolak Peminjaman</h3>
            <form id="rejectForm" method="POST" action="">
                <?php echo csrf_field(); ?>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Alasan Penolakan</label>
                    <textarea name="keterangan_penolakan"
                        class="w-full border rounded-lg p-2 focus:ring-black focus:border-black" rows="3"
                        required></textarea>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeRejectModal()"
                        class="px-4 py-2 text-gray-600 hover:text-gray-900">Batal</button>
                    <button type="submit"
                        class="bg-white text-red-600 border border-red-200 px-4 py-2 rounded-lg hover:bg-red-50">Tolak</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openApproveModal(id, itemName, units) {
            document.getElementById('approveModal').classList.remove('hidden');
            document.getElementById('approveForm').action = `/admin/peminjaman/${id}/approve`;
            document.getElementById('approveItemName').innerText = `Barang: ${itemName}`;

            const select = document.getElementById('unitSelect');
            select.innerHTML = '<option value="">-- Pilih Unit --</option>';
            Object.entries(units).forEach(([unitId, kode]) => {
                select.innerHTML += `<option value="${unitId}">${kode}</option>`;
            });
        }

        function closeApproveModal() {
            document.getElementById('approveModal').classList.add('hidden');
        }

        function openRejectModal(id) {
            document.getElementById('rejectModal').classList.remove('hidden');
            document.getElementById('rejectForm').action = `/admin/peminjaman/${id}/reject`;
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
        }
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\PROJECT-UKK\resources\views/admin/peminjaman/index.blade.php ENDPATH**/ ?>