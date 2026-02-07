<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Activity Log Report</title>
    <style>
        @page {
            margin: 40px 50px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 9px;
            line-height: 1.3;
            color: #1f2937;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #111827;
        }

        .header h1 {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 3px;
        }

        .header p {
            color: #6b7280;
            font-size: 9px;
            margin: 2px 0;
        }

        .filters {
            background: #f3f4f6;
            padding: 6px 10px;
            margin-bottom: 10px;
            border-radius: 3px;
            font-size: 8px;
        }

        .filters p {
            color: #4b5563;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        th {
            background: #374151;
            color: white;
            padding: 5px 4px;
            text-align: left;
            font-size: 8px;
            text-transform: uppercase;
            font-weight: 600;
        }

        td {
            padding: 4px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 8px;
            vertical-align: top;
        }

        tr:nth-child(even) {
            background: #f9fafb;
        }

        .action-badge {
            display: inline-block;
            padding: 1px 4px;
            background: #e5e7eb;
            border-radius: 2px;
            font-size: 7px;
        }

        .summary {
            font-size: 8px;
            color: #6b7280;
            margin-top: 10px;
            padding-top: 8px;
            border-top: 1px solid #e5e7eb;
        }

        .footer {
            position: fixed;
            bottom: 15px;
            left: 50px;
            right: 50px;
            text-align: center;
            font-size: 7px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
            padding-top: 5px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>LAPORAN ACTIVITY LOG</h1>
        <p>SIAPRAS - Sistem Informasi Aset dan Prasarana</p>
        <p>Digenerate: {{ $generatedAt }}</p>
    </div>

    @if($filters['action'] || $filters['user_id'] || $filters['start_date'] || $filters['end_date'])
        <div class="filters">
            <p><strong>Filter:</strong>
                @if($filters['action']) {{ $filters['action'] }} @endif
                @if($filters['start_date']) | Dari: {{ $filters['start_date'] }} @endif
                @if($filters['end_date']) - {{ $filters['end_date'] }} @endif
            </p>
        </div>
    @endif

    <table>
        <thead>
            <tr>
                <th style="width: 12%;">Waktu</th>
                <th style="width: 18%;">User</th>
                <th style="width: 12%;">Aksi</th>
                <th style="width: 58%;">Deskripsi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($activities as $activity)
                <tr>
                    <td>{{ $activity->created_at->format('d/m/y H:i') }}</td>
                    <td>{{ $activity->user->nama_lengkap ?? $activity->user->email ?? '-' }}</td>
                    <td><span class="action-badge">{{ $activity->action }}</span></td>
                    <td>{{ Str::limit($activity->description, 80) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center; padding: 15px;">Tidak ada data aktivitas</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary">
        Total: {{ $activities->count() }} aktivitas
    </div>

    <div class="footer">
        SIAPRAS Activity Log Report
    </div>
</body>

</html>