<!DOCTYPE html>
<html>

<head>
    <title>Laporan Pengaduan</title>
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
            vertical-align: top;
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
    <h2>Laporan Pengaduan Kerusakan</h2>
    <div class="meta">
        <p>Periode: {{ \Carbon\Carbon::parse($start_date)->format('d/m/Y') }} -
            {{ \Carbon\Carbon::parse($end_date)->format('d/m/Y') }}</p>
        <p>Dicetak Tanggal: {{ date('d/m/Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Pelapor</th>
                <th width="20%">Judul / Lokasi</th>
                <th width="30%">Deskripsi</th>
                <th width="15%">Tanggal</th>
                <th width="15%">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        {{ $item->user->nama_lengkap }}<br>
                        <small>{{ $item->user->role }}</small>
                    </td>
                    <td>
                        <strong>{{ $item->judul }}</strong><br>
                        {{ $item->lokasi }}
                    </td>
                    <td>{{ $item->deskripsi }}</td>
                    <td>{{ $item->created_at->format('d/m/Y') }}</td>
                    <td>{{ ucfirst($item->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>