@extends('layouts.main')

@section('title', $barang->nama_barang . ' - Katalog')

@section('content')
    <div class="max-w-5xl mx-auto">
        <!-- Back Button -->
        <a href="{{ route('katalog.index') }}"
            class="inline-flex items-center gap-2 text-gray-500 hover:text-gray-900 mb-6 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Katalog
        </a>

        <!-- Barang Detail Card -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-8">
            <div class="md:flex">
                <!-- Image -->
                <div class="md:w-1/3 bg-gray-50 flex items-center justify-center p-8">
                    @if($barang->gambar)
                        <img src="{{ Str::startsWith($barang->gambar, 'http') ? $barang->gambar : asset('storage/' . $barang->gambar) }}"
                            alt="{{ $barang->nama_barang }}" class="max-h-64 object-contain rounded-lg">
                    @else
                        <div class="w-32 h-32 bg-gray-200 rounded-lg flex items-center justify-center">
                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    @endif
                </div>

                <!-- Info -->
                <div class="md:w-2/3 p-6 md:p-8">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">{{ $barang->nama_barang }}</h1>
                            <div class="flex flex-wrap gap-2">
                                <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm font-medium">
                                    {{ $barang->kategori->nama_kategori }}
                                </span>
                                @if($barang->jenis_aset == 'tik')
                                    <span class="bg-black text-white px-3 py-1 rounded-full text-sm font-medium">TIK</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-500 mb-1">Lokasi</p>
                            <p class="font-semibold text-gray-900">{{ $barang->ruangan->nama_ruangan ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 mb-1">Stok Tersedia</p>
                            <p class="font-semibold {{ $barang->jumlah_stok > 0 ? 'text-green-600' : 'text-red-500' }}">
                                {{ $barang->jumlah_stok }} Unit
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-500 mb-1">Tipe Barang</p>
                            <p class="font-semibold text-gray-900">
                                {{ $barang->tipe_barang == 'maintenance' ? 'Bisa Diperbaiki' : 'Sekali Pakai' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-500 mb-1">Total Unit</p>
                            <p class="font-semibold text-gray-900">{{ $barang->units->count() }} Unit</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Unit List -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-lg font-bold text-gray-900">Daftar Unit Tersedia</h2>
                <p class="text-sm text-gray-500">Pilih unit yang ingin dipinjam</p>
            </div>

            <div class="divide-y divide-gray-100">
                @forelse($barang->units as $unit)
                    @php
                        $isBorrowed = in_array($unit->id, $borrowedUnitIds);
                        $isAvailable = $unit->status === 'aktif' && !$isBorrowed;
                        $statusClass = match ($unit->status) {
                            'aktif' => $isBorrowed ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700',
                            'maintenance' => 'bg-yellow-100 text-yellow-700',
                            'rusak' => 'bg-red-100 text-red-700',
                            default => 'bg-gray-100 text-gray-700'
                        };
                        $kondisiClass = match ($unit->kondisi) {
                            'Baik' => 'bg-green-100 text-green-700',
                            'Rusak Ringan' => 'bg-yellow-100 text-yellow-700',
                            'Rusak Berat' => 'bg-red-100 text-red-700',
                            default => 'bg-gray-100 text-gray-700'
                        };
                    @endphp
                    <div
                        class="p-4 sm:p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-gray-50 transition">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <span class="font-mono bg-gray-100 text-gray-800 px-3 py-1 rounded text-sm font-medium">
                                    {{ $unit->kode_unit }}
                                </span>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <span class="text-xs px-2 py-1 rounded-full {{ $kondisiClass }}">
                                    Kondisi: {{ $unit->kondisi }}
                                </span>
                                <span class="text-xs px-2 py-1 rounded-full {{ $statusClass }}">
                                    @if($isBorrowed)
                                        Sedang Dipinjam
                                    @else
                                        Status: {{ ucfirst($unit->status) }}
                                    @endif
                                </span>
                            </div>
                        </div>

                        <div class="flex-shrink-0">
                            @if($isAvailable)
                                <button onclick="addToCart({{ $unit->id }})"
                                    class="inline-flex items-center gap-2 bg-white border border-gray-200 text-gray-700 px-5 py-2.5 rounded-lg font-medium text-sm hover:bg-black hover:text-white hover:border-black transition group-btn">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    Tambah ke Keranjang
                                </button>
                            @else
                                <button disabled
                                    class="inline-flex items-center gap-2 bg-gray-100 text-gray-400 px-5 py-2.5 rounded-lg font-medium text-sm cursor-not-allowed">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                    </svg>
                                    @if($isBorrowed)
                                        Sedang Dipinjam
                                    @elseif($unit->status === 'maintenance')
                                        Maintenance
                                    @else
                                        Tidak Tersedia
                                    @endif
                                </button>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        <p>Tidak ada unit tersedia untuk barang ini</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function addToCart(unitId) {
            fetch('{{ route('cart.add') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ unit_id: unitId })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update cart badge if exists, or show alert
                        alert(data.message);
                        // Reload to update UI (optional, or update generic badge)
                        location.reload();
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menambahkan ke keranjang.');
                });
        }
    </script>
@endpush