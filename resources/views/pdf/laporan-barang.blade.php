<!DOCTYPE html>
<html>

<head>
    <title>Laporan Aset & Kesehatan Barang</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 10pt;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 5px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .section-title {
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
            font-size: 12pt;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>Laporan Aset & Kesehatan Barang</h2>
        <p>Tanggal: {{ \Carbon\Carbon::parse($date)->format('d F Y') }}</p>
    </div>

    <!-- 1. Ringkasan Kerusakan & Kehilangan -->
    <div class="section-title">1. Daftar Alat Rusak & Hilang (Current Status)</div>
    @if($damagedItems->isEmpty())
        <p>Tidak ada alat yang tercatat rusak atau hilang saat ini.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Kode Unit</th>
                    <th>Nama Barang</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($damagedItems as $item)
                    <tr>
                        <td>{{ $item->kode_unit }}</td>
                        <td>{{ $item->barang->nama_barang }}</td>
                        <td>
                            @if($item->status == 'rusak')
                                <span style="color: red;">Rusak</span>
                            @elseif($item->status == 'hilang')
                                <span style="color: red; font-weight: bold;">Hilang</span>
                            @elseif($item->status == 'maintenance')
                                <span style="color: orange;">Maintenance</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- 2. Alat Sering Rusak -->
    <div class="section-title">2. Top Barang Paling Sering Rusak/Bermasalah</div>
    @if($mostProblematic->isEmpty())
        <p>Belum ada riwayat kerusakan yang signifikan.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Total Kejadian (Rusak/Hilang/Maintenance)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($mostProblematic as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->nama_barang }}</td>
                        <td>{{ $item->total_rusak }} kali</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- 3. Daftar Seluruh Unit (Detail) -->
    <div class="section-title">3. Daftar Status Seluruh Unit</div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Kode Unit</th>
                <th>Kondisi Saat Ini</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->barang->nama_barang }}</td>
                    <td>{{ $item->kode_unit }}</td>
                    <td>{{ ucfirst($item->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>