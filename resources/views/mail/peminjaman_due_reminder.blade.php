<!DOCTYPE html>
<html>

<head>
    <title>Reminder Peminjaman</title>
</head>

<body>
    <div style="background-color: #f3f4f6; padding: 40px 0;">
        <div
            style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
            <!-- Header -->
            <div style="background-color: #000000; padding: 30px 40px; text-align: center;">
                <h1 style="color: #ffffff; margin: 0; font-size: 24px; letter-spacing: 1px;">SIAPRAS</h1>
                <p style="color: #9ca3af; margin: 5px 0 0; font-size: 14px;">SMKN 1 Boyolangu</p>
            </div>

            <!-- Content -->
            <div style="padding: 40px;">
                <h2 style="color: #111827; margin-top: 0; font-size: 20px;">
                    ⚠️ Reminder Pengembalian
                </h2>
                <p style="color: #4b5563; line-height: 1.6; margin-bottom: 24px;">
                    Halo, {{ $notifiable->nama_lengkap ?? $notifiable->email }}!<br><br>
                    Ini adalah pengingat bahwa batas waktu pengembalian barang pinjaman Anda akan segera berakhir atau
                    sudah jatuh tempo.
                </p>

                <div
                    style="background-color: #fff1f2; border: 1px solid #fecaca; border-radius: 12px; padding: 16px; margin-bottom: 24px; color: #9f1239; text-align: center; font-weight: 600;">
                    Status:
                    @if($daysUntilDue == 0)
                        🚨 JATUH TEMPO HARI INI
                    @elseif($daysUntilDue == 1)
                        ⚠️ BESOK
                    @else
                        📅 {{ $daysUntilDue }} Hari Lagi
                    @endif
                </div>

                <div
                    style="background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 12px; padding: 24px; margin-bottom: 32px;">
                    <h3
                        style="color: #374151; font-size: 14px; text-transform: uppercase; letter-spacing: 0.05em; margin: 0 0 16px 0;">
                        Detail Peminjaman
                    </h3>
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <td style="padding: 8px 0; color: #6b7280; font-size: 14px; width: 140px;">Kode Peminjaman
                            </td>
                            <td style="padding: 8px 0; color: #111827; font-size: 14px; font-weight: 600;">
                                {{ $peminjaman->kode }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 8px 0; color: #6b7280; font-size: 14px;">Barang</td>
                            <td style="padding: 8px 0; color: #111827; font-size: 14px; font-weight: 600;">
                                {{ $peminjaman->barang->nama_barang ?? 'Barang' }}
                                <span
                                    style="color: #6b7280; font-weight: normal;">({{ $peminjaman->barangUnit->kode_unit ?? '-' }})</span>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 8px 0; color: #6b7280; font-size: 14px;">Batas Kembali</td>
                            <td style="padding: 8px 0; color: #111827; font-size: 14px; font-weight: 600;">
                                {{ $peminjaman->tgl_kembali_rencana->format('d M Y') }}
                            </td>
                        </tr>
                    </table>
                </div>

                <p style="color: #4b5563; line-height: 1.6; margin-bottom: 24px; font-size: 14px;">
                    Mohon segera kembalikan barang tepat waktu untuk menghindari denda atau sanksi keterlambatan.
                    Abaikan pesan ini jika Anda sudah mengembalikan barang.
                </p>

                <div style="text-align: center;">
                    <a href="{{ url('/peminjaman/' . $peminjaman->id) }}"
                        style="display: inline-block; background-color: #000000; color: #ffffff; text-decoration: none; padding: 14px 32px; border-radius: 8px; font-weight: 600; font-size: 14px;">
                        Lihat Detail Peminjaman
                    </a>
                </div>
            </div>

            <!-- Footer -->
            <div style="background-color: #f9fafb; padding: 24px; text-align: center; border-top: 1px solid #e5e7eb;">
                <p style="color: #9ca3af; font-size: 12px; margin: 0;">&copy; {{ date('Y') }} SIAPRAS SMKN 1 Boyolangu.
                    All rights reserved.</p>
            </div>
        </div>
    </div>
</body>

</html>