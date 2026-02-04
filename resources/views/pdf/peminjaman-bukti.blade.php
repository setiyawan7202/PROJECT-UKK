<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Bukti Peminjaman</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            padding: 30px;
            font-size: 11px;
            color: #333;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 16px;
            font-weight: bold;
            margin: 0;
        }

        .header h2 {
            font-size: 12px;
            margin: 2px 0;
            font-weight: normal;
        }

        .title {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        .barcode {
            text-align: center;
            margin-bottom: 20px;
        }

        .barcode img {
            width: 90px;
            height: 90px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        td {
            vertical-align: top;
            padding: 4px;
        }

        .bordered th,
        .bordered td {
            border: 1px solid #ccc;
            padding: 6px;
            text-align: left;
        }

        .bordered th {
            background: #f5f5f5;
        }

        .section-header {
            font-weight: bold;
            padding-bottom: 5px;
            margin-bottom: 5px;
            border-bottom: 1px solid #eee;
            text-transform: uppercase;
            font-size: 10px;
            color: #555;
        }

        .footer {
            margin-top: 20px;
        }

        .signature-table {
            width: 100%;
            margin-top: 20px;
            border: none;
        }

        .signature-table td {
            text-align: center;
            vertical-align: bottom;
            height: 80px;
        }

        .rules {
            font-size: 9px;
            color: #555;
            margin-top: 10px;
            border: 1px solid #eee;
            padding: 10px;
            background: #fcfcfc;
        }

        .rules ol {
            margin: 0;
            padding-left: 15px;
        }

        .rules li {
            margin-bottom: 2px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>SIAPRAS SMKN 1 BOYOLANGU</h1>
        <h2>Sistem Informasi Sarana dan Prasarana</h2>
    </div>

    <div class="title">Bukti Peminjaman Barang</div>

    <div class="barcode">
        @if(isset($qrImage) && $qrImage)
            <img src="{{ $qrImage }}" alt="QR Code">
        @else
            <div
                style="border:1px dashed #ccc; width:90px; height:90px; margin:0 auto; display:flex; align-items:center; justify-content:center; line-height:90px;">
                No QR</div>
        @endif
        <div style="font-family: monospace; margin-top: 5px;">{{ $peminjaman->kode }}</div>
    </div>

    <div class="section-header">Informasi</div>
    <table>
        <tr>
            <td width="20%">Nama Peminjam</td>
            <td width="30%">: {{ $peminjaman->user->nama_lengkap ?? $peminjaman->user->username }}</td>
            <td width="20%">Kode Unit</td>
            <td width="30%">: <b>{{ $peminjaman->barangUnit->kode_unit ?? '-' }}</b></td>
        </tr>
        <tr>
            <td>Jabatan</td>
            <td>:
                @if($peminjaman->user->role == 'siswa')
                    Siswa {{ $peminjaman->user->siswa->kelas->nama_kelas ?? '' }}
                @elseif($peminjaman->user->role == 'guru')
                    Guru
                @else
                    {{ ucfirst($peminjaman->user->role) }}
                @endif
            </td>
            <td>Kondisi Awal</td>
            <td>: {{ ucfirst($peminjaman->barangUnit->kondisi ?? '-') }}</td>
        </tr>
    </table>

    <div class="section-header">Detail Barang & Peminjaman</div>
    <table class="bordered">
        <thead>
            <tr>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Tgl Pinjam</th>
                <th>Rencana Kembali</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $peminjaman->barang->nama_barang }}</td>
                <td>{{ $peminjaman->barang->kategori->nama_kategori ?? '-' }}</td>
                <td>{{ $peminjaman->tgl_pinjam->format('d M Y') }}</td>
                <td>{{ $peminjaman->tgl_kembali_rencana->format('d M Y') }}</td>
            </tr>
        </tbody>
    </table>

    @if($peminjaman->tujuan_pinjam)
        <div class="section-header">Keperluan</div>
        <div style="font-size: 10px; background: #f9f9f9; padding: 8px; border: 1px solid #eee; margin-bottom: 15px;">
            {{ $peminjaman->tujuan_pinjam }}
        </div>
    @endif

    <div class="rules">
        <strong>Ketentuan Peminjaman:</strong>
        <ol>
            <li>Peminjam bertanggung jawab penuh atas barang yang dipinjam.</li>
            <li>Barang harus dikembalikan tepat waktu sesuai dengan tanggal rencana kembali.</li>
            <li>Segala kerusakan atau kehilangan barang menjadi tanggung jawab peminjam dan wajib mengganti atau
                memperbaiki sesuai kebijakan sekolah.</li>
            <li>Bukti ini wajib dibawa saat pengembalian barang.</li>
            <li>Pihak Sarpras berhak menarik barang sewaktu-waktu jika diperlukan mendesak.</li>
        </ol>
    </div>
</body>

</html>