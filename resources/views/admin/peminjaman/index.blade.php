@extends('layouts.admin')

@section('title', 'Manajemen Peminjaman')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Manajemen Peminjaman</h1>
        <p class="text-sm text-gray-500">Kelola persetujuan dan status peminjaman.</p>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">
            {{ session('error') }}
        </div>
    @endif

    <!-- Filters -->
    <div class="bg-white p-4 rounded-xl border border-gray-100 mb-6">
        <form method="GET" action="{{ route('admin.peminjaman.index') }}" class="flex gap-4">
            <select name="status" class="px-4 py-2 border rounded-lg text-sm" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </form>
    </div>

    <div class="bg-white border border-gray-100 rounded-xl overflow-hidden">
        <div class="overflow-x-auto w-full">
            <table class="w-full min-w-[1000px] text-left text-sm whitespace-nowrap">
                <thead class="bg-gray-50 text-gray-500">
                    <tr>
                        <th class="px-6 py-3 font-semibold">Peminjam</th>
                        <th class="px-6 py-3 font-semibold">Barang</th>
                        <th class="px-6 py-3 font-semibold">Tgl Pinjam</th>
                        <th class="px-6 py-3 font-semibold">Tujuan</th>
                        <th class="px-6 py-3 font-semibold">Status</th>
                        <th class="px-6 py-3 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($peminjaman as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ $item->user->nama_lengkap }}</div>
                                <div class="text-xs text-gray-500">{{ $item->user->kelas->nama_kelas ?? 'Staff/Guru' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium">{{ $item->barang->nama_barang }}</div>
                                <div class="text-xs text-gray-500">
                                    Unit: {{ $item->barangUnit->kode_unit ?? '(Belum dialokasikan)' }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div>{{ $item->tgl_pinjam->format('d/m/Y') }}</div>
                                <div class="text-xs text-gray-400">s/d {{ $item->tgl_kembali_rencana->format('d/m/Y') }}</div>
                            </td>
                            <td class="px-6 py-4 text-gray-500 max-w-xs truncate" title="{{ $item->tujuan_pinjam }}">
                                {{ Str::limit($item->tujuan_pinjam, 30) }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $colors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'approved' => 'bg-blue-100 text-blue-800',
                                        'active' => 'bg-green-100 text-green-800',
                                        'completed' => 'bg-gray-100 text-gray-800',
                                        'rejected' => 'bg-red-100 text-red-800',
                                        'overdue' => 'bg-red-100 text-red-800',
                                    ];
                                @endphp
                                <span
                                    class="px-2 py-1 rounded-full text-xs font-semibold {{ $colors[$item->status] ?? 'bg-gray-100' }}">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if($item->status == 'pending')
                                    <div class="flex justify-end gap-2">
                                        <button
                                            onclick="openApproveModal('{{ $item->id }}', '{{ $item->barang->nama_barang }}', {{ $item->barang->units->where('status', 'aktif')->pluck('kode_unit', 'id') }})"
                                            class="bg-blue-600 text-white px-3 py-1 rounded-lg text-xs hover:bg-blue-700">
                                            Approve
                                        </button>
                                        <button onclick="openRejectModal('{{ $item->id }}')"
                                            class="bg-red-600 text-white px-3 py-1 rounded-lg text-xs hover:bg-red-700">
                                            Reject
                                        </button>
                                    </div>
                                @elseif($item->status == 'approved')
                                    <div class="flex justify-end gap-2">
                                        <form action="{{ route('admin.peminjaman.activate', $item->id) }}" method="POST"
                                            onsubmit="return confirm('Konfirmasi pengambilan barang?')">
                                            @csrf
                                            <button type="submit"
                                                class="bg-green-600 text-white px-3 py-1 rounded-lg text-xs hover:bg-green-700">
                                                Ambil Barang
                                            </button>
                                        </form>
                                        <a href="{{ route('admin.peminjaman.bukti', $item->id) }}" target="_blank"
                                            class="bg-gray-600 text-white px-3 py-1 rounded-lg text-xs hover:bg-gray-700">
                                            Cetak Bukti
                                        </a>
                                    </div>
                                @elseif($item->status == 'active')
                                    <a href="{{ route('admin.peminjaman.return', $item->id) }}"
                                        class="bg-black text-white px-3 py-1 rounded-lg text-xs hover:bg-gray-800">
                                        Kembalikan
                                    </a>
                                    <a href="{{ route('admin.peminjaman.bukti', $item->id) }}" target="_blank"
                                        class="bg-gray-600 text-white px-3 py-1 rounded-lg text-xs hover:bg-gray-700">
                                        Cetak Bukti
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">Tidak ada data peminjaman.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100">
            {{ $peminjaman->links() }}
        </div>
    </div>

    <!-- Modal Approve -->
    <div id="approveModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white rounded-xl p-6 w-full max-w-md">
            <h3 class="text-lg font-bold mb-4">Setujui Peminjaman</h3>
            <p class="text-sm text-gray-600 mb-4" id="approveItemName"></p>

            <form id="approveForm" method="POST" action="">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Pilih Unit Barang</label>
                    <select name="barang_unit_id" id="unitSelect" class="w-full border rounded-lg p-2" required>
                        <!-- Populated via JS -->
                    </select>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeApproveModal()" class="px-4 py-2 text-gray-600">Batal</button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg">Setujui</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Reject -->
    <div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white rounded-xl p-6 w-full max-w-md">
            <h3 class="text-lg font-bold mb-4">Tolak Peminjaman</h3>
            <form id="rejectForm" method="POST" action="">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Alasan Penolakan</label>
                    <textarea name="keterangan_penolakan" class="w-full border rounded-lg p-2" rows="3" required></textarea>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeRejectModal()" class="px-4 py-2 text-gray-600">Batal</button>
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg">Tolak</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openApproveModal(id, itemName, units) {
            document.getElementById('approveModal').classList.remove('hidden');
            document.getElementById('approveForm').action = `/admin/peminjaman/${id}/approve`;
            document.getElementById('approveItemName').innerText = `Barang: ${itemName}`;

            const select = document.getElementById('unitSelect');
            select.innerHTML = '<option value="">-- Pilih Unit --</option>';
            Object.entries(units).forEach(([unitId, kode]) => {
                select.innerHTML += `<option value="${unitId}">${kode}</option>`;
            });
        }

        function closeApproveModal() {
            document.getElementById('approveModal').classList.add('hidden');
        }

        function openRejectModal(id) {
            document.getElementById('rejectModal').classList.remove('hidden');
            document.getElementById('rejectForm').action = `/admin/peminjaman/${id}/reject`;
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
        }
    </script>
@endsection