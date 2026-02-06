@extends('layouts.main')

@section('title', 'Detail Peminjaman')

@section('content')
    <div class="mb-6 flex items-center gap-4">
        <a href="{{ route('peminjaman.index') }}" class="p-2 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Detail Peminjaman</h1>
            <p class="text-sm text-gray-500">Kode: <span class="font-mono font-bold text-gray-900">{{ $peminjaman->kode }}</span></p>
        </div>
    </div>

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm">
                <div class="flex justify-between items-start mb-6">
                    <h3 class="text-lg font-bold text-gray-900">Informasi Barang</h3>
                    @php
                        $statusColors = [
                            'pending' => 'bg-gray-100 text-gray-600 border border-gray-200',
                            'approved' => 'bg-black text-white border border-black',
                            'active' => 'bg-gray-800 text-white border border-gray-800',
                            'completed' => 'bg-white text-gray-800 border border-gray-300',
                            'rejected' => 'bg-white text-red-600 border border-red-200',
                        ];
                        $statusLabels = [
                            'pending' => 'Menunggu Persetujuan',
                            'approved' => 'Disetujui',
                            'active' => 'Sedang Dipinjam',
                            'completed' => 'Selesai',
                            'rejected' => 'Ditolak',
                        ];
                    @endphp
                    <span class="px-3 py-1 rounded-md text-sm font-bold {{ $statusColors[$peminjaman->status] ?? 'bg-gray-100' }}">
                        {{ $statusLabels[$peminjaman->status] ?? ucfirst($peminjaman->status) }}
                    </span>
                </div>

                <div class="flex gap-6">
                    <div class="w-24 h-24 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0 border border-gray-200">
                        @if($peminjaman->barang->gambar)
                            @if(str_starts_with($peminjaman->barang->gambar, 'http'))
                                <img src="{{ $peminjaman->barang->gambar }}" alt="{{ $peminjaman->barang->nama_barang }}" class="w-full h-full object-cover">
                            @else
                                <img src="{{ asset('storage/' . $peminjaman->barang->gambar) }}" alt="{{ $peminjaman->barang->nama_barang }}" class="w-full h-full object-cover">
                            @endif
                        @else
                            <div class="flex items-center justify-center h-full text-gray-400">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-lg text-gray-900">{{ $peminjaman->barang->nama_barang }}</h4>
                        <p class="text-sm text-gray-500 mb-3">{{ $peminjaman->barang->kategori->nama_kategori ?? '-' }}</p>

                        @if($peminjaman->barangUnit)
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <span class="text-xs text-gray-500 block">Unit Dipinjam</span>
                                    <span class="font-mono text-sm font-semibold bg-gray-100 px-2 py-1 rounded">{{ $peminjaman->barangUnit->kode_unit }}</span>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500 block">Kondisi</span>
                                    <span class="text-sm font-medium">{{ ucfirst($peminjaman->barangUnit->kondisi ?? 'Baik') }}</span>
                                </div>
                            </div>
                        @else
                            <p class="text-sm text-gray-500 italic">Unit belum dialokasikan</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm">
                <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-50">Detail Peminjaman</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-6">
                    <div>
                        <dt class="text-xs text-gray-500 mb-1">Tanggal Pengajuan</dt>
                        <dd class="text-sm font-medium">{{ $peminjaman->created_at->format('d F Y, H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500 mb-1">Jumlah</dt>
                        <dd class="text-sm font-medium">{{ $peminjaman->jumlah }} unit</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500 mb-1">Tanggal Pinjam</dt>
                        <dd class="text-sm font-medium">{{ $peminjaman->tgl_pinjam->format('d F Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500 mb-1">Rencana Kembali</dt>
                        <dd class="text-sm font-medium">{{ $peminjaman->tgl_kembali_rencana->format('d F Y') }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-xs text-gray-500 mb-1">Tujuan Peminjaman</dt>
                        <dd class="text-sm text-gray-700 bg-gray-50 p-3 rounded-lg border border-gray-100">
                            {{ $peminjaman->tujuan_pinjam }}
                        </dd>
                    </div>

                    @if($peminjaman->status == 'rejected' && $peminjaman->keterangan_penolakan)
                        <div class="sm:col-span-2">
                            <dt class="text-xs text-red-500 mb-1 font-bold">Alasan Penolakan</dt>
                            <dd class="text-sm text-red-700 bg-red-50 p-3 rounded-lg border border-red-100">
                                {{ $peminjaman->keterangan_penolakan }}
                            </dd>
                        </div>
                    @endif

                    @if($peminjaman->status == 'completed' && $peminjaman->pengembalian)
                        <div class="sm:col-span-2 mt-4 pt-4 border-t border-gray-100">
                            <h4 class="font-bold text-gray-900 mb-4">Informasi Pengembalian</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <span class="text-xs text-gray-500 block">Tanggal Kembali Aktual</span>
                                    <span class="font-medium text-sm">{{ $peminjaman->pengembalian->tgl_kembali->format('d F Y') }}</span>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500 block">Kondisi Akhir</span>
                                    <span class="font-medium text-sm">{{ ucfirst(str_replace('_', ' ', $peminjaman->pengembalian->kondisi)) }}</span>
                                </div>
                                @if($peminjaman->pengembalian->denda > 0)
                                    <div>
                                        <span class="text-xs text-gray-500 block">Denda</span>
                                        <span class="font-bold text-red-600 text-sm">Rp {{ number_format($peminjaman->pengembalian->denda, 0, ',', '.') }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </dl>
            </div>
        </div>

        <div class="space-y-6">
            @if(in_array($peminjaman->status, ['approved', 'active', 'completed']))
                <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-50">Bukti Peminjaman</h3>

                    <div class="text-center mb-4">
                        <div class="inline-block p-4 bg-white border-2 border-gray-200 rounded-lg">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ $peminjaman->kode }}" 
                                 alt="QR Code {{ $peminjaman->kode }}" class="w-24 h-24 mx-auto">
                            <p class="text-xs font-mono mt-2 text-gray-600">{{ $peminjaman->kode }}</p>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-3 rounded-lg text-xs text-gray-600 mb-4 border border-gray-100">
                        @if($peminjaman->status == 'approved')
                            <p class="font-semibold text-gray-900 mb-1">✅ Peminjaman Disetujui</p>
                            <p>Silakan ambil barang di ruang inventaris dengan menunjukkan bukti ini.</p>
                        @elseif($peminjaman->status == 'active')
                            <p class="font-semibold text-gray-900 mb-1">📦 Barang Sedang Dipinjam</p>
                            <p>Jatuh tempo pengembalian: {{ $peminjaman->tgl_kembali_rencana->format('d M Y') }}</p>
                        @else
                            <p class="font-semibold text-gray-900 mb-1">✓ Peminjaman Selesai</p>
                            <p>Barang telah dikembalikan pada {{ $peminjaman->pengembalian->tgl_kembali->format('d M Y') }}</p>
                        @endif
                    </div>

                    <div class="flex flex-col gap-3">
                        <a href="{{ route('peminjaman.cetak', $peminjaman->id) }}" target="_blank"
                           class="block w-full text-center py-2.5 bg-black hover:bg-gray-800 text-white rounded-lg text-sm font-bold transition shadow-sm flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            Cetak Bukti
                        </a>
                        
                        <a href="{{ route('peminjaman.download-pdf', $peminjaman->id) }}" 
                           class="block w-full text-center py-2.5 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-lg text-sm font-medium transition shadow-sm flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Download PDF
                        </a>
                    </div>
                </div>
            @endif

            <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm">
                <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-50">Status Peminjaman</h3>
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 rounded-full bg-black text-white flex items-center justify-center text-xs font-bold flex-shrink-0">✓</div>
                        <div>
                            <p class="font-medium text-gray-900">Pengajuan Dikirim</p>
                            <p class="text-xs text-gray-500">{{ $peminjaman->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>

                    @if(in_array($peminjaman->status, ['approved', 'active', 'completed']))
                        <div class="flex items-start gap-3">
                            <div class="w-6 h-6 rounded-full bg-black text-white flex items-center justify-center text-xs font-bold flex-shrink-0">✓</div>
                            <div>
                                <p class="font-medium text-gray-900">Disetujui Admin</p>
                                <p class="text-xs text-gray-500">Unit: {{ $peminjaman->barangUnit->kode_unit ?? '-' }}</p>
                            </div>
                        </div>
                    @elseif($peminjaman->status == 'rejected')
                        <div class="flex items-start gap-3">
                            <div class="w-6 h-6 rounded-full bg-gray-300 text-white flex items-center justify-center text-xs font-bold flex-shrink-0">✗</div>
                            <div>
                                <p class="font-medium text-gray-500">Ditolak</p>
                                <p class="text-xs text-red-500">{{ $peminjaman->keterangan_penolakan }}</p>
                            </div>
                        </div>
                    @else
                        <div class="flex items-start gap-3">
                            <div class="w-6 h-6 rounded-full bg-gray-200 text-gray-400 flex items-center justify-center text-xs font-bold flex-shrink-0">2</div>
                            <div>
                                <p class="font-medium text-gray-400">Menunggu Persetujuan</p>
                            </div>
                        </div>
                    @endif

                    @if(in_array($peminjaman->status, ['active', 'completed']))
                        <div class="flex items-start gap-3">
                            <div class="w-6 h-6 rounded-full bg-black text-white flex items-center justify-center text-xs font-bold flex-shrink-0">✓</div>
                            <div>
                                <p class="font-medium text-gray-900">Barang Diambil</p>
                                <p class="text-xs text-gray-500">{{ $peminjaman->tgl_pinjam->format('d M Y') }}</p>
                            </div>
                        </div>
                    @elseif($peminjaman->status == 'approved')
                        <div class="flex items-start gap-3">
                            <div class="w-6 h-6 rounded-full bg-gray-200 text-gray-400 flex items-center justify-center text-xs font-bold flex-shrink-0">3</div>
                            <div>
                                <p class="font-medium text-gray-400">Menunggu Pengambilan</p>
                            </div>
                        </div>
                    @endif

                    @if($peminjaman->status == 'completed')
                        <div class="flex items-start gap-3">
                            <div class="w-6 h-6 rounded-full bg-black text-white flex items-center justify-center text-xs font-bold flex-shrink-0">✓</div>
                            <div>
                                <p class="font-medium text-gray-900">Dikembalikan</p>
                                <p class="text-xs text-gray-500">{{ $peminjaman->pengembalian->tgl_kembali->format('d M Y') }}</p>
                            </div>
                        </div>
                    @elseif($peminjaman->status == 'active')
                        <div class="flex items-start gap-3">
                            <div class="w-6 h-6 rounded-full bg-gray-200 text-gray-400 flex items-center justify-center text-xs font-bold flex-shrink-0">4</div>
                            <div>
                                <p class="font-medium text-gray-400">Pengembalian</p>
                                <p class="text-xs text-gray-400">Jatuh tempo: {{ $peminjaman->tgl_kembali_rencana->format('d M Y') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
