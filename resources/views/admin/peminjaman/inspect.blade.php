@extends('layouts.admin')

@section('title', 'Inspeksi Peminjaman')

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.peminjaman.show', $peminjaman->id) }}"
            class="text-gray-500 hover:text-gray-700 text-sm flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali ke Detail
        </a>
        <h1 class="text-xl lg:text-2xl font-bold text-gray-900 mt-2">
            Inspeksi {{ $inspectionType === 'pre_borrow' ? 'Pra-Peminjaman' : 'Pasca-Pengembalian' }}
        </h1>
        <p class="text-sm text-gray-500">Peminjaman: {{ $peminjaman->kode }}</p>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Info Barang -->
    <div class="bg-white rounded-xl border border-gray-100 p-6 mb-6">
        <h3 class="font-semibold text-gray-900 mb-4">Informasi Barang</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
            <div>
                <span class="text-gray-500">Barang:</span>
                <p class="font-medium">{{ $peminjaman->barang->nama_barang }}</p>
            </div>
            <div>
                <span class="text-gray-500">Kategori:</span>
                <p class="font-medium">{{ $peminjaman->barang->kategori->nama ?? '-' }}</p>
            </div>
            <div>
                <span class="text-gray-500">Jumlah:</span>
                <p class="font-medium">{{ $peminjaman->jumlah }} unit</p>
            </div>
        </div>
    </div>

    <!-- Existing Inspections -->
    @if($preInspection)
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mb-6">
            <h3 class="font-semibold text-blue-800 mb-3">Inspeksi Pra-Peminjaman (Selesai)</h3>
            <div class="text-sm text-blue-700">
                <p>Dilakukan oleh: {{ $preInspection->inspector->nama_lengkap ?? '-' }}</p>
                <p>Tanggal: {{ $preInspection->inspected_at->format('d M Y H:i') }}</p>
                @if($preInspection->checklist_data)
                    <div class="mt-3">
                        <p class="font-medium mb-2">Hasil Checklist:</p>
                        <ul class="space-y-1">
                            @foreach($preInspection->checklist_data as $key => $value)
                                <li class="flex items-center gap-2">
                                    @if($value)
                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    @endif
                                    {{ $key }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    @endif

    @if($postInspection)
        <div class="bg-green-50 border border-green-200 rounded-xl p-6 mb-6">
            <h3 class="font-semibold text-green-800 mb-3">Inspeksi Pasca-Pengembalian (Selesai)</h3>
            <div class="text-sm text-green-700">
                <p>Dilakukan oleh: {{ $postInspection->inspector->nama_lengkap ?? '-' }}</p>
                <p>Tanggal: {{ $postInspection->inspected_at->format('d M Y H:i') }}</p>
                @if($postInspection->has_damage)
                    <div class="mt-3 p-3 bg-red-100 rounded-lg text-red-700">
                        <p class="font-medium">⚠️ Kerusakan Terdeteksi!</p>
                        <p class="text-xs mt-1">{{ $postInspection->damage_details }}</p>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Inspection Form -->
    @if($inspectionType)
        <div class="bg-white rounded-xl border border-gray-100 p-6">
            <h3 class="font-semibold text-gray-900 mb-4">
                Form Inspeksi {{ $inspectionType === 'pre_borrow' ? 'Pra-Peminjaman' : 'Pasca-Pengembalian' }}
            </h3>

            <form action="{{ route('admin.peminjaman.storeInspection', $peminjaman->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="type" value="{{ $inspectionType }}">

                <!-- Checklist -->
                @if($template && $template->items)
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">Checklist Kondisi</label>
                        <div class="space-y-3">
                            @foreach($template->items as $item)
                                <label
                                    class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                                    <input type="checkbox" name="checklist_data[{{ $item['key'] }}]" value="1"
                                        class="w-5 h-5 rounded border-gray-300 text-black focus:ring-black">
                                    <span class="text-sm text-gray-700">{{ $item['label'] }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg text-yellow-700 text-sm">
                        Tidak ada template checklist untuk kategori ini.
                        <a href="{{ route('admin.checklist-templates.create') }}" class="underline">Buat template</a>
                    </div>
                @endif

                <!-- Photo -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Foto Dokumentasi</label>
                    <input type="file" name="photo" accept="image/*"
                        class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm">
                    <p class="text-xs text-gray-500 mt-1">Ambil foto kondisi barang saat ini (maks 2MB)</p>
                </div>

                <!-- Notes -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                    <textarea name="notes" rows="3" class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm"
                        placeholder="Catatan tambahan tentang kondisi barang..."></textarea>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('admin.peminjaman.show', $peminjaman->id) }}"
                        class="px-4 py-2 border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-50">
                        Batal
                    </a>
                    <button type="submit" class="px-4 py-2 bg-black text-white rounded-lg hover:bg-gray-800 transition">
                        Simpan Inspeksi
                    </button>
                </div>
            </form>
        </div>
    @else
        <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 text-center text-gray-500">
            <p>Tidak ada inspeksi yang perlu dilakukan saat ini.</p>
        </div>
    @endif
@endsection