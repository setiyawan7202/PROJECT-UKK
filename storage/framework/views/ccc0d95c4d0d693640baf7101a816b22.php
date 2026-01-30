<!DOCTYPE html>
<html>

<head>
    <title>Laporan Status Aset</title>
    <style>
        body {
            font-family: sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-size: 12px;
        }

        th {
            background-color: #f2f2f2;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        h3 {
            margin-top: 20px;
            margin-bottom: 10px;
            font-size: 14px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
        }

        .meta {
            margin-bottom: 20px;
            font-size: 12px;
        }

        .status-aktif {
            color: green;
            font-weight: bold;
        }

        .status-maintenance {
            color: orange;
            font-weight: bold;
        }

        .status-rusak {
            color: red;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <h2>Laporan Status Kondisi Aset</h2>
    <div class="meta">
        <p>Tanggal Laporan: <?php echo e(\Carbon\Carbon::parse($date)->format('d F Y')); ?></p>
    </div>

    <?php
        $currentBarang = null;
    ?>

    <table>
        <thead>
            <tr>
                <th>Nama Barang</th>
                <th>Kode Unit</th>
                <th>Kondisi Fisik</th>
                <th>Status Sistem</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($unit->barang->nama_barang); ?></td>
                    <td><?php echo e($unit->kode_unit); ?></td>
                    <td><?php echo e(ucfirst($unit->kondisi)); ?></td>
                    <td>
                        <span class="status-<?php echo e($unit->status); ?>">
                            <?php echo e(ucfirst($unit->status)); ?>

                        </span>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</body>

</html><?php /**PATH D:\laragon\www\PROJECT-UKK\resources\views/admin/laporan/pdf_barang.blade.php ENDPATH**/ ?>