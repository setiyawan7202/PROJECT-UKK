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
        <p>Tanggal Laporan: {{ \Carbon\Carbon::parse($date)->format('d F Y') }}</p>
    </div>

    @php
        $currentBarang = null;
    @endphp

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
            @foreach($data as $unit)
                <tr>
                    <td>{{ $unit->barang->nama_barang }}</td>
                    <td>{{ $unit->kode_unit }}</td>
                    <td>{{ ucfirst($unit->kondisi) }}</td>
                    <td>
                        <span class="status-{{ $unit->status }}">
                            {{ ucfirst($unit->status) }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>