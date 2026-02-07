<!DOCTYPE html>
<html>

<head>
    <title>Jadwal Maintenance</title>
</head>

<body>
    <div style="background-color: #f3f4f6; padding: 40px 0;">
        <div
            style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
            <div style="background-color: #000000; padding: 30px 40px; text-align: center;">
                <h1 style="color: #ffffff; margin: 0; font-size: 24px; letter-spacing: 1px;">SIAPRAS</h1>
                <p style="color: #9ca3af; margin: 5px 0 0; font-size: 14px;">Maintenance Reminder</p>
            </div>

            <div style="padding: 40px;">
                <h2 style="color: #111827; margin-top: 0; font-size: 20px;">
                    🛠️ Jadwal Maintenance
                </h2>
                <p style="color: #4b5563; line-height: 1.6; margin-bottom: 24px;">
                    Halo Admin/Teknisi,<br><br>
                    Ini adalah pengingat untuk jadwal maintenance aset sekolah.
                </p>

                <div
                    style="background-color: #fff1f2; border: 1px solid #fecaca; border-radius: 12px; padding: 16px; margin-bottom: 24px; color: #9f1239; text-align: center; font-weight: 600;">
                    Status:
                    @if($daysUntil == 0)
                        🚨 HARI INI
                    @elseif($daysUntil < 0)
                        ⚠️ TERLAMBAT {{ abs($daysUntil) }} HARI
                    @else
                        📅 {{ $daysUntil }} Hari Lagi
                    @endif
                </div>

                <div
                    style="background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 12px; padding: 24px; margin-bottom: 32px;">
                    <h3
                        style="color: #374151; font-size: 14px; text-transform: uppercase; letter-spacing: 0.05em; margin: 0 0 16px 0;">
                        Detail Aset
                    </h3>
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <td style="padding: 8px 0; color: #6b7280; font-size: 14px; width: 140px;">Target</td>
                            <td style="padding: 8px 0; color: #111827; font-size: 14px; font-weight: 600;">
                                {{ $schedule->kategori ? $schedule->kategori->nama_kategori : ($schedule->barang ? $schedule->barang->nama_barang : 'Unknown') }}
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 8px 0; color: #6b7280; font-size: 14px;">Jadwal</td>
                            <td style="padding: 8px 0; color: #111827; font-size: 14px; font-weight: 600;">
                                {{ $schedule->next_maintenance_at->format('d M Y') }}
                            </td>
                        </tr>
                    </table>
                </div>

                <div style="text-align: center;">
                    <a href="{{ url('/admin/maintenance') }}"
                        style="display: inline-block; background-color: #000000; color: #ffffff; text-decoration: none; padding: 14px 32px; border-radius: 8px; font-weight: 600; font-size: 14px;">
                        Buka Jadwal Maintenance
                    </a>
                </div>
            </div>

            <div style="background-color: #f9fafb; padding: 24px; text-align: center; border-top: 1px solid #e5e7eb;">
                <p style="color: #9ca3af; font-size: 12px; margin: 0;">&copy; {{ date('Y') }} SIAPRAS SMKN 1 Boyolangu.
                </p>
            </div>
        </div>
    </div>
</body>

</html>