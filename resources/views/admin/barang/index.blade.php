@extends('layouts.admin')

@section('title', 'Manajemen Barang')

@section('content')
    <div class="mb-6 lg:mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl lg:text-2xl font-bold text-gray-900">Manajemen Barang</h1>
            <p class="text-sm text-gray-500">Daftar sarana dan prasarana</p>
        </div>
        <a href="{{ route('admin.barang.create') }}"
            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-black text-white rounded-xl font-medium text-sm hover:bg-gray-800 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Tambah Barang
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-4 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white border border-gray-100 rounded-xl overflow-hidden">
        <div class="p-4 border-b border-gray-100">
            <form action="{{ route('admin.barang.index') }}" method="GET" class="flex flex-col md:flex-row gap-3">
                <div class="relative flex-1">
                    <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama barang..."
                        class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-1 focus:ring-black">
                </div>
                <div class="w-full md:w-48">
                    <select name="kategori_id"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-white focus:outline-none focus:ring-1 focus:ring-black">
                        <option value="">Semua Kategori</option>
                        @foreach($kategoris as $cat)
                            <option value="{{ $cat->id }}" {{ request('kategori_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="w-full md:w-48">
                    <select name="ruangan_id"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-white focus:outline-none focus:ring-1 focus:ring-black">
                        <option value="">Semua Ruangan</option>
                        @foreach($ruangans as $room)
                            <option value="{{ $room->id }}" {{ request('ruangan_id') == $room->id ? 'selected' : '' }}>
                                {{ $room->nama_ruangan }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="w-full md:w-48">
                    <select name="jenis_aset"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-white focus:outline-none focus:ring-1 focus:ring-black">
                        <option value="">Semua Jenis Aset</option>
                        <option value="tik" {{ request('jenis_aset') == 'tik' ? 'selected' : '' }}>Aset TIK</option>
                        <option value="non_tik" {{ request('jenis_aset') == 'non_tik' ? 'selected' : '' }}>Non-TIK</option>
                    </select>
                </div>
                <button type="submit"
                    class="bg-gray-100 px-6 py-2 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-200 transition">Filter</button>
            </form>
        </div>

        <div class="overflow-x-auto w-full">
            <table class="w-full min-w-[1000px] text-left text-sm whitespace-nowrap">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 border-b border-gray-100">
                        <th class="px-6 py-3 font-semibold text-xs uppercase">Kode</th>
                        <th class="px-6 py-3 font-semibold text-xs uppercase">Gambar</th>
                        <th class="px-6 py-3 font-semibold text-xs uppercase">Nama Barang</th>
                        <th class="px-6 py-3 font-semibold text-xs uppercase">Kategori</th>
                        <th class="px-6 py-3 font-semibold text-xs uppercase">Ruangan</th>
                        <th class="px-6 py-3 font-semibold text-xs uppercase">Jumlah Unit</th>
                        <th class="px-6 py-3 font-semibold text-xs uppercase text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($barangs as $barang)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <span
                                    class="font-mono bg-gray-100 text-gray-800 px-2 py-1 rounded text-xs">{{ $barang->kode ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($barang->gambar)
                                    <button
                                        onclick="showImageModal('{{ asset('storage/' . $barang->gambar) }}', '{{ $barang->nama_barang }}')"
                                        class="group cursor-pointer">
                                        <img src="{{ asset('storage/' . $barang->gambar) }}" alt="{{ $barang->nama_barang }}"
                                            class="w-12 h-12 object-cover rounded-lg border border-gray-200 group-hover:border-black transition">
                                    </button>
                                @else
                                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900">
                                <div>{{ $barang->nama_barang }}</div>
                                <div class="flex gap-1 mt-1">
                                    <span
                                        class="text-[10px] px-2 py-0.5 rounded-full {{ $barang->jenis_aset == 'tik' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600' }}">
                                        {{ $barang->jenis_aset == 'tik' ? 'TIK' : 'Non-TIK' }}
                                    </span>
                                    <span
                                        class="text-[10px] px-2 py-0.5 rounded-full {{ $barang->tipe_barang == 'maintenance' ? 'bg-purple-100 text-purple-700' : 'bg-orange-100 text-orange-700' }}">
                                        {{ $barang->tipe_barang == 'maintenance' ? 'Bisa Diperbaiki' : 'Sekali Pakai' }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-500">
                                <span
                                    class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs">{{ $barang->kategori->nama_kategori ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4 text-gray-500">{{ $barang->ruangan->nama_ruangan ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-flex items-center gap-1 font-medium {{ $barang->units->count() == 0 ? 'text-red-500' : 'text-gray-900' }}">
                                    {{ $barang->units->count() }} unit
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.barang.show', $barang->id) }}"
                                        class="p-2 text-blue-500 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition"
                                        title="Lihat Detail Unit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.barang.edit', $barang->id) }}"
                                        class="p-2 text-gray-500 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition"
                                        title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.barang.destroy', $barang->id) }}" method="POST"
                                        onsubmit="return confirm('Hapus barang ini beserta semua unitnya?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition"
                                            title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                                <p>Belum ada data barang</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="hidden fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4"
        onclick="closeImageModal()">
        <div class="relative max-w-4xl max-h-[90vh]" onclick="event.stopPropagation()">
            <button onclick="closeImageModal()" class="absolute -top-10 right-0 text-white hover:text-gray-300 transition">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <img id="modalImage" src="" alt="" class="max-w-full max-h-[85vh] rounded-lg">
            <p id="modalTitle" class="text-white text-center mt-3 font-medium"></p>
        </div>
    </div>

    <script>
        function showImageModal(imageSrc, title) {
            document.getElementById('imageModal').classList.remove('hidden');
            document.getElementById('modalImage').src = imageSrc;
            document.getElementById('modalTitle').textContent = title;
            document.body.style.overflow = 'hidden';
        }

        function closeImageModal() {
            document.getElementById('imageModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Close modal on ESC key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeImageModal();
            }
        });
    </script>
@endsection