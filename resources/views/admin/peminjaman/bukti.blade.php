<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Bukti Peminjaman</title>
    <style>
        @page {
            margin: 20px;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .header p {
            margin: 5px 0;
            font-size: 12px;
        }

        .content {
            margin: 20px 0;
        }

        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }

        .info-table td {
            padding: 8px 0;
            font-size: 14px;
        }

        .info-table td:first-child {
            width: 200px;
            font-weight: bold;
        }

        .qr-section {
            text-align: center;
            margin: 30px 0;
            padding: 20px;
            border: 2px dashed #ccc;
        }

        .footer {
            margin-top: 40px;
            font-size: 12px;
        }

        .signature {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
        }

        .signature div {
            text-align: center;
            width: 45%;
        }

        .signature-line {
            margin-top: 60px;
            border-top: 1px solid #000;
            padding-top: 5px;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 5px;
            font-weight: bold;
            font-size: 12px;
        }

        .status-approved {
            background-color: #d4edda;
            color: #155724;
        }

        .status-active {
            background-color: #d1ecf1;
            color: #0c5460;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>BUKTI PEMINJAMAN</h1>
        <p>Sistem Informasi Sarana Prasarana</p>
        <p>SMKN 1 Boyolangu</p>
    </div>

    <div class="content">
        <table class="info-table">
            <tr>
                <td>Nomor Peminjaman</td>
                <td>: #{{ str_pad($peminjaman->id, 5, '0', STR_PAD_LEFT) }}</td>
            </tr>
            <tr>
                <td>Tanggal Peminjaman</td>
                <td>: {{ $peminjaman->tgl_pinjam->format('d F Y') }}</td>
            </tr>
            <tr>
                <td>Status</td>
                <td>: <span
                        class="status-badge status-{{ $peminjaman->status }}">{{ strtoupper($peminjaman->status) }}</span>
                </td>
            </tr>
        </table>

        <h3 style="margin-top: 30px; border-bottom: 2px solid #000; padding-bottom: 5px;">Data Peminjam</h3>
        <table class="info-table">
            <tr>
                <td>Nama Lengkap</td>
                <td>: {{ $peminjaman->user->nama_lengkap }}</td>
            </tr>
            <tr>
                <td>Email</td>
                <td>: {{ $peminjaman->user->email }}</td>
            </tr>
            <tr>
                <td>Role</td>
                <td>: {{ ucfirst($peminjaman->user->role) }}</td>
            </tr>
            @if($peminjaman->user->siswa)
                <tr>
                    <td>Kelas</td>
                    <td>: {{ $peminjaman->user->siswa->kelas->nama_kelas ?? '-' }}</td>
                </tr>
            @endif
        </table>

        <h3 style="margin-top: 30px; border-bottom: 2px solid #000; padding-bottom: 5px;">Detail Barang</h3>
        <table class="info-table">
            <tr>
                <td>Nama Barang</td>
                <td>: {{ $peminjaman->barang->nama_barang }}</td>
            </tr>
            <tr>
                <td>Kategori</td>
                <td>: {{ $peminjaman->barang->kategori->nama ?? '-' }}</td>
            </tr>
            @if($peminjaman->barangUnit)
                <tr>
                    <td>Kode Unit</td>
                    <td>: <strong>{{ $peminjaman->barangUnit->kode_unit }}</strong></td>
                </tr>
            @endif
            <tr>
                <td>Jumlah</td>
                <td>: {{ $peminjaman->jumlah }} unit</td>
            </tr>
            <tr>
                <td>Tujuan Peminjaman</td>
                <td>: {{ $peminjaman->tujuan_pinjam }}</td>
            </tr>
        </table>

        <h3 style="margin-top: 30px; border-bottom: 2px solid #000; padding-bottom: 5px;">Jadwal Pengembalian</h3>
        <table class="info-table">
            <tr>
                <td>Tanggal Kembali (Rencana)</td>
                <td>: {{ $peminjaman->tgl_kembali_rencana->format('d F Y') }}</td>
            </tr>
            @if($peminjaman->tgl_kembali_aktual)
                <tr>
                    <td>Tanggal Kembali (Aktual)</td>
                    <td>: {{ $peminjaman->tgl_kembali_aktual->format('d F Y') }}</td>
                </tr>
            @endif
        </table>

        <div class="qr-section">
            <p style="margin: 0 0 10px 0; font-weight: bold;">Scan QR Code untuk Verifikasi</p>
            {!! QrCode::size(150)->generate(route('admin.peminjaman.index') . '?id=' . $peminjaman->id) !!}
            <p style="margin: 10px 0 0 0; font-size: 12px; color: #666;">ID: {{ $peminjaman->id }}</p>
        </div>

        <div class="footer">
            <p><strong>Catatan Penting:</strong></p>
            <ul style="margin: 5px 0; padding-left: 20px; font-size: 12px;">
                <li>Harap mengembalikan barang sesuai jadwal yang telah ditentukan</li>
                <li>Barang yang dikembalikan harus dalam kondisi baik</li>
                <li>Kerusakan atau kehilangan menjadi tanggung jawab peminjam</li>
                <li>Simpan bukti ini sebagai tanda peminjaman yang sah</li>
            </ul>
        </div>

        <div class="signature">
            <div>
                <p>Peminjam,</p>
                <div class="signature-line">{{ $peminjaman->user->nama_lengkap }}</div>
            </div>
            <div>
                <p>Petugas,</p>
                <div class="signature-line">(...........................)</div>
            </div>
        </div>
    </div>

    <script>
        window.onload = function () {
            // Auto print when page loads (optional)
            // window.print();
        }
    </script>
</body>

</html>