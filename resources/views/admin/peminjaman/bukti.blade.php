<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Peminjaman - {{ $peminjaman->barang->nama_barang }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print {
                display: none;
            }

            body {
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
</head>

<body class="bg-gray-100 p-8">
    <div class="max-w-2xl mx-auto bg-white p-8 shadow-lg print:shadow-none">
        <!-- Header -->
        <div class="flex items-center justify-between border-b-2 border-black pb-4 mb-6">
            <div class="flex items-center gap-4">
                <img src="https://via.placeholder.com/60" alt="Logo Sekolah" class="w-16 h-16">
                <div>
                    <h1 class="text-xl font-bold uppercase">SMK TEKNOLOGI DIGITAL</h1>
                    <p class="text-sm">Jl. Pendidikan No. 123, Kota Teknologi</p>
                    <p class="text-xs">Telp: (021) 1234567 | Email: info@smktekno.sch.id</p>
                </div>
            </div>
            <div class="text-right">
                <h2 class="text-2xl font-bold text-gray-800">BUKTI PEMINJAMAN</h2>
                <p class="font-mono text-sm mt-1">#PINJAM-{{ str_pad($peminjaman->id, 5, '0', STR_PAD_LEFT) }}</p>
            </div>
        </div>

        <!-- Info Peminjam & Barang -->
        <div class="grid grid-cols-2 gap-8 mb-6">
            <div>
                <h3 class="font-bold text-gray-600 mb-2 border-b pb-1">DATA PEMINJAM</h3>
                <table class="text-sm w-full">
                    <tr>
                        <td class="py-1 w-24 text-gray-500">Nama</td>
                        <td class="font-medium">: {{ $peminjaman->user->nama_lengkap }}</td>
                    </tr>
                    <tr>
                        <td class="py-1 text-gray-500">Kelas/Jabatan</td>
                        <td class="font-medium">: {{ $peminjaman->user->kelas->nama_kelas ?? 'Guru/Staff' }}</td>
                    </tr>
                    <tr>
                        <td class="py-1 text-gray-500">Email</td>
                        <td>: {{ $peminjaman->user->email }}</td>
                    </tr>
                </table>
            </div>
            <div>
                <h3 class="font-bold text-gray-600 mb-2 border-b pb-1">DETAIL BARANG</h3>
                <table class="text-sm w-full">
                    <tr>
                        <td class="py-1 w-24 text-gray-500">Nama Barang</td>
                        <td class="font-medium">: {{ $peminjaman->barang->nama_barang }}</td>
                    </tr>
                    <tr>
                        <td class="py-1 text-gray-500">Kategori</td>
                        <td>: {{ $peminjaman->barang->kategori->nama_kategori }}</td>
                    </tr>
                    <tr>
                        <td class="py-1 text-gray-500">Kode Unit</td>
                        <td class="font-bold font-mono text-blue-600">: {{ $peminjaman->barangUnit->kode_unit ?? '-' }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Detail Waktu -->
        <div class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
            <h3 class="font-bold text-gray-600 mb-2 text-sm">WAKTU PEMINJAMAN</h3>
            <div class="grid grid-cols-3 gap-4 text-center">
                <div>
                    <span class="block text-xs text-gray-500">Tanggal Pinjam</span>
                    <span class="font-bold">{{ $peminjaman->tgl_pinjam->format('d M Y') }}</span>
                </div>
                <div>
                    <span class="block text-xs text-gray-500">Rencana Kembali</span>
                    <span class="font-bold">{{ $peminjaman->tgl_kembali_rencana->format('d M Y') }}</span>
                </div>
                <div>
                    <span class="block text-xs text-gray-500">Status Saat Ini</span>
                    <span class="font-bold uppercase text-blue-600">{{ $peminjaman->status }}</span>
                </div>
            </div>
        </div>

        <!-- Tujuan -->
        <div class="mb-8">
            <h3 class="font-bold text-gray-600 mb-2 border-b pb-1">KEPERLUAN</h3>
            <p class="text-sm italic text-gray-700">"{{ $peminjaman->tujuan_pinjam }}"</p>
        </div>

        <!-- Tanda Tangan -->
        <div class="flex justify-between mt-12 mb-8">
            <div class="text-center w-40">
                <p class="text-sm mb-16">Peminjam,</p>
                <p class="font-bold underline text-sm">{{ $peminjaman->user->nama_lengkap }}</p>
            </div>
            <div class="text-center w-40">
                <p class="text-sm mb-16">Petugas Sarpras,</p>
                <p class="font-bold underline text-sm">( ........................... )</p>
            </div>
        </div>

        <!-- Footer / QR Placeholder -->
        <div class="border-t pt-4 flex items-center justify-between">
            <p class="text-xs text-gray-400">Dicetak pada: {{ date('d-m-Y H:i') }}</p>
            <div class="text-right">
                <div
                    class="border border-gray-300 w-16 h-16 flex items-center justify-center text-xs text-gray-400 ml-auto bg-white">
                    QR CODE
                </div>
                <p class="text-[10px] text-gray-400 mt-1">Scan untuk validasi</p>
            </div>
        </div>
    </div>

    <!-- Print Button -->
    <div class="fixed bottom-8 right-8 no-print">
        <button onclick="window.print()"
            class="bg-blue-600 text-white px-6 py-3 rounded-full shadow-lg hover:bg-blue-700 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            Cetak Bukti
        </button>
    </div>
</body>

</html>