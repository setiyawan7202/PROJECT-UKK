@extends('layouts.staff')

@section('title', 'Manajemen Peminjaman')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Manajemen Peminjaman</h1>
        <p class="text-sm text-gray-500">Kelola persetujuan dan status peminjaman.</p>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-gray-50 border border-gray-200 text-gray-800 rounded-xl text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-4 bg-white border border-gray-200 text-red-600 rounded-xl text-sm">
            {{ session('error') }}
        </div>
    @endif

    <!-- Filters -->
    <div class="bg-white p-4 rounded-xl border border-gray-100 mb-6">
        <form method="GET" action="{{ route('staff.peminjaman.index') }}" class="flex gap-4">
            <select name="status" class="px-4 py-2 border rounded-lg text-sm focus:ring-black focus:border-black"
                onchange="this.form.submit()">
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
                        <th class="px-6 py-3 font-semibold">Kode</th>
                        <th class="px-6 py-3 font-semibold">Peminjam</th>
                        <th class="px-6 py-3 font-semibold">Barang</th>
                        <th class="px-6 py-3 font-semibold">Tgl Pinjam</th>
                        <th class="px-6 py-3 font-semibold">Jumlah</th>
                        <th class="px-6 py-3 font-semibold">Tujuan</th>
                        <th class="px-6 py-3 font-semibold">Status</th>
                        <th class="px-6 py-3 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($peminjaman as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <span class="font-mono text-xs font-bold text-gray-900">{{ $item->kode ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ $item->user->nama_lengkap }}</div>
                                <div class="text-xs text-gray-500">
                                    {{ $item->user->siswa->kelas->nama_kelas ?? ucfirst($item->user->role) }}
                                </div>
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
                            <td class="px-6 py-4">
                                <span class="font-bold text-gray-900">{{ $item->jumlah }}</span> Unit
                            </td>
                            <td class="px-6 py-4 text-gray-500 max-w-xs truncate" title="{{ $item->tujuan_pinjam }}">
                                {{ Str::limit($item->tujuan_pinjam, 30) }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $colors = [
                                        'pending' => 'bg-gray-100 text-gray-600 border border-gray-200',
                                        'approved' => 'bg-black text-white border border-black',
                                        'active' => 'bg-gray-800 text-white border border-gray-800',
                                        'completed' => 'bg-white text-gray-800 border border-gray-300',
                                        'rejected' => 'bg-white text-red-600 border border-red-200',
                                        'overdue' => 'bg-red-50 text-red-700 border border-red-200',
                                    ];
                                @endphp
                                <span
                                    class="px-2 py-1 rounded-md text-xs font-semibold {{ $colors[$item->status] ?? 'bg-gray-100' }}">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if($item->status == 'pending')
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('staff.peminjaman.show', $item->id) }}"
                                            class="bg-white border border-gray-200 text-gray-700 px-3 py-1 rounded-lg text-xs hover:bg-gray-50 font-medium">
                                            Detail
                                        </a>
                                        <button onclick='openApproveModal("{{ $item->id }}", "{{ $item->barang->nama_barang }}", {{ $item->jumlah }}, @json($item->barang->units->where("status", "aktif")->map(function ($u) {
                                            return ["id" => $u->id, "kode" => $u->kode_unit];
                                        })->values()))'
                                            class="bg-black text-white px-3 py-1 rounded-lg text-xs hover:bg-gray-800">
                                            Approve
                                        </button>
                                        <button onclick="openRejectModal('{{ $item->id }}')"
                                            class="bg-white text-gray-700 border border-gray-300 px-3 py-1 rounded-lg text-xs hover:bg-gray-50">
                                            Reject
                                        </button>
                                    </div>
                                @elseif($item->status == 'approved')
                                    <!-- Approved Actions -->
                                    <div class="flex justify-end gap-2">
                                        <form action="{{ route('staff.peminjaman.activate', $item->id) }}" method="POST"
                                            onsubmit="return confirm('Konfirmasi pengambilan barang?')">
                                            @csrf
                                            <button type="submit"
                                                class="bg-black text-white px-3 py-1 rounded-lg text-xs hover:bg-gray-800">
                                                Ambil Barang
                                            </button>
                                        </form>
                                        <a href="{{ route('staff.peminjaman.show', $item->id) }}"
                                            class="bg-white border border-gray-200 text-gray-700 px-3 py-1 rounded-lg text-xs hover:bg-gray-50 font-medium">
                                            Detail
                                        </a>
                                        <a href="{{ route('staff.peminjaman.bukti', $item->id) }}" target="_blank"
                                            class="bg-white border border-gray-200 text-gray-700 px-3 py-1 rounded-lg text-xs hover:bg-gray-50">
                                            Cetak Bukti
                                        </a>
                                    </div>
                                @elseif($item->status == 'active')
                                    <!-- Active Actions -->
                                    <a href="{{ route('staff.peminjaman.return', $item->id) }}"
                                        class="bg-black text-white px-3 py-1 rounded-lg text-xs hover:bg-gray-800">
                                        Kembalikan
                                    </a>
                                    <a href="{{ route('staff.peminjaman.show', $item->id) }}"
                                        class="bg-white border border-gray-200 text-gray-700 px-3 py-1 rounded-lg text-xs hover:bg-gray-50 font-medium">
                                        Detail
                                    </a>
                                    <a href="{{ route('staff.peminjaman.bukti', $item->id) }}" target="_blank"
                                        class="bg-white border border-gray-200 text-gray-700 px-3 py-1 rounded-lg text-xs hover:bg-gray-50">
                                        Cetak Bukti
                                    </a>
                                @else
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('staff.peminjaman.show', $item->id) }}"
                                            class="bg-white border border-gray-200 text-gray-700 px-3 py-1 rounded-lg text-xs hover:bg-gray-50 font-medium">
                                            Detail
                                        </a>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-gray-500">Tidak ada data peminjaman.</td>
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
        <div class="bg-white rounded-xl p-6 w-full max-w-lg shadow-2xl transform transition-all">
            <h3 class="text-lg font-bold mb-4">Setujui Peminjaman</h3>
            <div class="mb-4 bg-gray-50 p-3 rounded-lg border border-gray-100">
                <p class="text-sm text-gray-600">Barang: <span class="font-bold text-gray-900" id="approveItemName"></span>
                </p>
                <div id="approveQtyInfo" class="text-sm text-gray-600 mt-1"></div>
            </div>

            <form id="approveForm" method="POST" action="">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Pilih Unit (Auto-Selected)</label>
                    <div id="unitInputsContainer" class="space-y-3 max-h-60 overflow-y-auto pr-1">
                        <!-- Unit selects will be inserted here -->
                    </div>
                </div>
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 mt-4">
                    <button type="button" onclick="closeApproveModal()"
                        class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium text-sm transition">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-black text-white rounded-lg hover:bg-gray-800 font-medium text-sm transition shadow-sm">Simpan
                        & Setujui</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Reject -->
    <div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white rounded-xl p-6 w-full max-w-md shadow-2xl">
            <h3 class="text-lg font-bold mb-4">Tolak Peminjaman</h3>
            <form id="rejectForm" method="POST" action="">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Alasan Penolakan</label>
                    <textarea name="keterangan_penolakan"
                        class="w-full border rounded-lg p-2 focus:ring-black focus:border-black" rows="3"
                        required></textarea>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeRejectModal()"
                        class="px-4 py-2 text-gray-600 hover:text-gray-900">Batal</button>
                    <button type="submit"
                        class="bg-white text-red-600 border border-red-200 px-4 py-2 rounded-lg hover:bg-red-50">Tolak</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openApproveModal(id, itemName, loanQty, availableUnits) {
            document.getElementById('approveModal').classList.remove('hidden');
            document.getElementById('approveForm').action = `/staff/peminjaman/${id}/approve`;
            document.getElementById('approveItemName').innerText = itemName;
            document.getElementById('approveQtyInfo').innerHTML = `Jumlah Diminta: <span class="font-bold text-gray-900">${loanQty} Unit</span>`;

            const container = document.getElementById('unitInputsContainer');
            container.innerHTML = ''; // Clear previous

            // Validation: Check stocks
            if (availableUnits.length < loanQty) {
                const missing = loanQty - availableUnits.length;
                container.innerHTML = `
                            <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-lg mb-4">
                                <p class="font-bold flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    Stok Tidak Mencukupi!
                                </p>
                                <p class="text-sm mt-1">Permintaan: <strong>${loanQty}</strong> unit. Tersedia: <strong>${availableUnits.length}</strong> unit.</p>
                                <p class="text-sm mt-1">Harap tolak permintaan ini atau minta user mengajukan ulang dengan jumlah sesuai stok.</p>
                            </div>`;

                // Disable submit button
                const submitBtn = document.querySelector('#approveForm button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                }
                return;
            }

            // Enable submit button
            const submitBtn = document.querySelector('#approveForm button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            }

            // Generate Unit Selects
            for (let i = 0; i < loanQty; i++) {
                // Auto-select logic: Assign unit at index i to input i
                let preSelectedId = availableUnits[i] ? availableUnits[i].id : '';

                let options = '<option value="">-- Pilih Unit --</option>';
                availableUnits.forEach(unit => {
                    const selected = unit.id == preSelectedId ? 'selected' : '';
                    options += `<option value="${unit.id}" ${selected}>${unit.kode}</option>`;
                });

                const div = document.createElement('div');
                div.className = "bg-gray-50 p-2 rounded-lg border border-gray-200 text-sm";
                div.innerHTML = `
                            <label class="block text-xs font-bold text-gray-500 mb-1">Unit Ke-${i + 1}</label>
                            <select name="barang_unit_ids[]" class="w-full bg-white border border-gray-300 rounded p-1.5 focus:ring-1 focus:ring-black focus:border-black" required>
                                ${options}
                            </select>
                        `;
                container.appendChild(div);
            }
        }

        function closeApproveModal() {
            document.getElementById('approveModal').classList.add('hidden');
        }

        function openRejectModal(id) {
            document.getElementById('rejectModal').classList.remove('hidden');
            document.getElementById('rejectForm').action = `/staff/peminjaman/${id}/reject`;
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
        }
    </script>
@endsection