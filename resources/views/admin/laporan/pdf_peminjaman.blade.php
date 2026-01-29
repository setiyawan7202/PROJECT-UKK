<!DOCTYPE html>
<html>

<head>
    <title>Laporan Peminjaman</title>
    <style>
        body {
            font-family: sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
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

        .meta {
            margin-bottom: 20px;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <h2>Laporan Peminjaman Sarana Prasarana</h2>
    <div class="meta">
        <p>Periode: {{ \Carbon\Carbon::parse($start_date)->format('d/m/Y') }} -
            {{ \Carbon\Carbon::parse($end_date)->format('d/m/Y') }}</p>
        <p>Dicetak Tanggal: {{ date('d/m/Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Peminjam</th>
                <th>Barang</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali (Rencana)</th>
                <th>Tgl Kembali (Aktual)</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->user->nama_lengkap }}</td>
                    <td>
                        {{ $item->barang->nama_barang }}
                        @if($item->barangUnit)
                            <br><small>({{ $item->barangUnit->kode_unit }})</small>
                        @endif
                    </td>
                    <td>{{ $item->tgl_pinjam->format('d/m/Y') }}</td>
                    <td>{{ $item->tgl_kembali_rencana->format('d/m/Y') }}</td>
                    <td>{{ $item->tgl_kembali_aktual ? $item->tgl_kembali_aktual->format('d/m/Y') : '-' }}</td>
                    <td>{{ ucfirst($item->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>