<!DOCTYPE html>
<html>

<head>
    <title>Selamat Datang di SIAPRAS</title>
</head>

<body>
    <div style="background-color: #f3f4f6; padding: 40px 0;">
        <div
            style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);">
            <!-- Header -->
            <div style="background-color: #000000; padding: 30px 40px; text-align: center;">
                <h1 style="color: #ffffff; margin: 0; font-size: 24px; letter-spacing: 1px;">SIAPRAS</h1>
                <p style="color: #9ca3af; margin: 5px 0 0; font-size: 14px;">SMKN 1 Boyolangu</p>
            </div>

            <!-- Content -->
            <div style="padding: 40px;">
                <h2 style="color: #111827; margin-top: 0; font-size: 20px;">Selamat Datang, {{ $name }}!</h2>
                <p style="color: #4b5563; line-height: 1.6; margin-bottom: 24px;">
                    Akun Anda telah berhasil dibuat. Anda sekarang dapat mengakses sistem inventaris sarana dan
                    prasarana sekolah.
                </p>

                <div
                    style="background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 12px; padding: 24px; margin-bottom: 32px;">
                    <h3
                        style="color: #374151; font-size: 14px; text-transform: uppercase; letter-spacing: 0.05em; margin: 0 0 16px 0;">
                        Detail Akun Anda</h3>

                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <td style="padding: 8px 0; color: #6b7280; font-size: 14px; width: 140px;">Nama Lengkap</td>
                            <td style="padding: 8px 0; color: #111827; font-size: 14px; font-weight: 600;">{{ $name }}
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 8px 0; color: #6b7280; font-size: 14px;">Email</td>
                            <td style="padding: 8px 0; color: #111827; font-size: 14px; font-weight: 600;">
                                {{ $user->email }}
                            </td>
                        </tr>
                        @if($user->data_nip_nisn)
                            <tr>
                                <td style="padding: 8px 0; color: #6b7280; font-size: 14px;">
                                    @if($user->status === 'siswa')
                                        NISN
                                    @elseif($user->status === 'guru')
                                        NIP
                                    @else
                                        ID Login
                                    @endif
                                </td>
                                <td style="padding: 8px 0; color: #111827; font-size: 14px; font-weight: 600;">
                                    {{ $user->data_nip_nisn }}
                                </td>
                            </tr>
                        @endif
                        @if($user->status === 'siswa' && $user->siswa && $user->siswa->kelas)
                            <tr>
                                <td style="padding: 8px 0; color: #6b7280; font-size: 14px;">Kelas</td>
                                <td style="padding: 8px 0; color: #111827; font-size: 14px; font-weight: 600;">
                                    {{ $user->siswa->kelas->nama_kelas ?? '-' }}
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <td style="padding: 8px 0; color: #6b7280; font-size: 14px;">Password</td>
                            <td style="padding: 8px 0;">
                                <span
                                    style="background-color: #e5e7eb; color: #374151; padding: 4px 8px; border-radius: 6px; font-family: monospace; font-size: 14px;">{{ $password }}</span>
                            </td>
                        </tr>
                    </table>
                </div>

                <div style="text-align: center;">
                    <a href="{{ url('/login') }}"
                        style="display: inline-block; background-color: #000000; color: #ffffff; text-decoration: none; padding: 14px 32px; border-radius: 8px; font-weight: 600; font-size: 14px; transition: background-color 0.2s;">
                        Masuk ke Aplikasi
                    </a>
                </div>

                <p style="text-align: center; margin-top: 32px; color: #6b7280; font-size: 14px;">
                    Harap segera ganti password Anda setelah login pertama demi keamanan.
                </p>
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