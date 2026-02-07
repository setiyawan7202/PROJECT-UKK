@extends('layouts.staff')

@section('title', 'Detail Peminjaman')

@section('content')
    <div class="mb-6 flex items-center gap-4">
        <a href="{{ route('staff.peminjaman.index') }}"
            class="p-2 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Detail Peminjaman</h1>
            <p class="text-sm text-gray-500">Kode: <span
                    class="font-mono font-bold text-gray-900">{{ $peminjaman->kode }}</span></p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-gray-50 border border-gray-200 text-gray-800 rounded-xl text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-white border border-gray-200 text-red-600 rounded-xl text-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Main Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Informasi Barang -->
            <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm">
                <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-50">Informasi Barang</h3>
                <div class="flex gap-6">
                    <div class="w-24 h-24 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0 border border-gray-200">
                        @if($peminjaman->barang->gambar)
                            @if(str_starts_with($peminjaman->barang->gambar, 'http'))
                                <img src="{{ $peminjaman->barang->gambar }}" alt="{{ $peminjaman->barang->nama_barang }}"
                                    class="w-full h-full object-cover">
                            @else
                                <img src="{{ asset('storage/' . $peminjaman->barang->gambar) }}"
                                    alt="{{ $peminjaman->barang->nama_barang }}" class="w-full h-full object-cover">
                            @endif
                        @else
                            <div class="flex items-center justify-center h-full text-gray-400">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-lg text-gray-900">{{ $peminjaman->barang->nama_barang }}</h4>
                        <div class="text-sm text-gray-500 mb-2">{{ $peminjaman->barang->kategori->nama_kategori ?? '-' }}
                        </div>

                        <div class="grid grid-cols-2 gap-4 mt-4">
                            <div>
                                <span class="text-xs text-gray-500 block">Unit Dipinjam</span>
                                <span class="font-mono text-sm font-semibold bg-gray-100 px-2 py-1 rounded">
                                    {{ $peminjaman->barangUnit->kode_unit ?? 'Belum dialokasikan' }}
                                </span>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500 block">Kondisi Awal</span>
                                <span
                                    class="text-sm font-medium">{{ ucfirst($peminjaman->barangUnit->kondisi ?? 'Baik') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informasi Peminjaman -->
            <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm">
                <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-50">Detail Peminjaman</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-6">
                    <div>
                        <dt class="text-xs text-gray-500 mb-1">Status</dt>
                        <dd>
                            @php
                                $statusColors = [
                                    'pending' => 'bg-gray-100 text-gray-600 border border-gray-200',
                                    'approved' => 'bg-black text-white border border-black',
                                    'active' => 'bg-gray-800 text-white border border-gray-800',
                                    'completed' => 'bg-white text-gray-800 border border-gray-300',
                                    'rejected' => 'bg-white text-red-600 border border-red-200',
                                ];
                            @endphp
                            <span
                                class="px-3 py-1 rounded-md text-xs font-bold {{ $statusColors[$peminjaman->status] ?? 'bg-gray-100' }}">
                                {{ ucfirst($peminjaman->status) }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500 mb-1">Jumlah</dt>
                        <dd class="text-sm font-bold text-gray-900">{{ $peminjaman->jumlah }} Barang</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500 mb-1">Tanggal Pengajuan</dt>
                        <dd class="text-sm font-medium">{{ $peminjaman->created_at->format('d F Y, H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500 mb-1">Rencana Pinjam</dt>
                        <dd class="text-sm font-medium">{{ $peminjaman->tgl_pinjam->format('d M Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500 mb-1">Rencana Kembali</dt>
                        <dd class="text-sm font-medium">{{ $peminjaman->tgl_kembali_rencana->format('d M Y') }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-xs text-gray-500 mb-1">Tujuan Peminjaman</dt>
                        <dd class="text-sm text-gray-700 bg-gray-50 p-3 rounded-lg border border-gray-100">
                            {{ $peminjaman->tujuan_pinjam }}
                        </dd>
                    </div>

                    @if($peminjaman->status == 'rejected')
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
                                    <span
                                        class="font-medium text-sm">{{ $peminjaman->pengembalian->tgl_kembali->format('d F Y') }}</span>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500 block">Kondisi Akhir</span>
                                    <span class="font-medium text-sm">{{ ucfirst($peminjaman->pengembalian->kondisi) }}</span>
                                </div>
                                @if($peminjaman->pengembalian->denda > 0)
                                    <div>
                                        <span class="text-xs text-gray-500 block">Denda</span>
                                        <span class="font-bold text-red-600 text-sm">Rp
                                            {{ number_format($peminjaman->pengembalian->denda, 0, ',', '.') }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </dl>
            </div>
        </div>

        <!-- Right Column: Borrower & Actions -->
        <div class="space-y-6">
            <!-- Informasi Peminjam -->
            <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm">
                <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-50">Informasi Peminjam</h3>
                <div class="flex items-center gap-4 mb-4">
                    <div
                        class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 text-lg font-bold">
                        {{ substr($peminjaman->user->nama_lengkap, 0, 1) }}
                    </div>
                    <div>
                        <div class="font-bold text-gray-900">{{ $peminjaman->user->nama_lengkap }}</div>
                        <div class="text-xs text-gray-500">{{ $peminjaman->user->email }}</div>
                    </div>
                </div>
                <dl class="space-y-3">
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500">Role</dt>
                        <dd class="text-sm font-medium">{{ ucfirst($peminjaman->user->role) }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500">Kelas/Jabatan</dt>
                        <dd class="text-sm font-medium">
                            {{ $peminjaman->user->siswa->kelas->nama_kelas ?? ucfirst($peminjaman->user->role) }}
                        </dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500">No. Telepon</dt>
                        <dd class="text-sm font-medium">{{ $peminjaman->user->no_in ?? '-' }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Aksi Petugas -->
            <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm">
                <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-50">Aksi</h3>

                @if($peminjaman->status == 'pending')
                    <div class="space-y-3">
                        <button onclick="openApproveModal()"
                            class="w-full py-2.5 bg-black hover:bg-gray-800 text-white rounded-lg text-sm font-medium transition flex justify-center items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Setujui Peminjaman
                        </button>
                        <button onclick="openRejectModal('{{ $peminjaman->id }}')"
                            class="w-full py-2.5 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-lg text-sm font-medium transition flex justify-center items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                                </path>
                            </svg>
                            Tolak Peminjaman
                        </button>
                    </div>
                @elseif($peminjaman->status == 'approved')
                    <div class="space-y-3">
                        <div
                            class="bg-gray-50 text-gray-800 border border-gray-100 p-3 rounded-lg text-xs leading-relaxed mb-3">
                            Peminjaman disetujui. Unit dialokasikan:
                            <strong>{{ $peminjaman->barangUnit->kode_unit ?? '-' }}</strong>.
                        </div>
                        <form action="{{ route('staff.peminjaman.activate', $peminjaman->id) }}" method="POST"
                            onsubmit="return confirm('Konfirmasi pengambilan barang?')">
                            @csrf
                            <button type="submit"
                                class="w-full py-2.5 bg-black hover:bg-gray-800 text-white rounded-lg text-sm font-medium transition shadow-sm mb-3">
                                Konfirmasi Barang Diambil
                            </button>
                        </form>

                        <a href="{{ route('staff.peminjaman.inspect', $peminjaman->id) }}"
                            class="block w-full text-center py-2.5 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg text-sm font-medium transition mb-3">
                            Lakukan Inspeksi Pra-Peminjaman
                        </a>

                        <a href="{{ route('staff.peminjaman.bukti', $peminjaman->id) }}" target="_blank"
                            class="block w-full text-center py-2.5 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 rounded-lg text-sm font-medium transition">
                            Cetak Bukti Peminjaman
                        </a>
                    </div>
                @elseif($peminjaman->status == 'active')
                    <div class="space-y-3">
                        <div
                            class="bg-gray-50 text-gray-800 border border-gray-100 p-3 rounded-lg text-xs leading-relaxed mb-3">
                            Jatuh tempo: {{ $peminjaman->tgl_kembali_rencana->format('d M Y') }}.
                        </div>

                        <a href="{{ route('staff.peminjaman.inspect', $peminjaman->id) }}"
                            class="block w-full text-center py-2.5 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg text-sm font-medium transition shadow-sm">
                            Inspeksi / Cek Kondisi
                        </a>

                        <a href="{{ route('staff.peminjaman.return', $peminjaman->id) }}"
                            class="block w-full text-center py-2.5 bg-black hover:bg-gray-800 text-white rounded-lg text-sm font-medium transition shadow-sm">
                            Proses Pengembalian
                        </a>
                        <a href="{{ route('staff.peminjaman.bukti', $peminjaman->id) }}" target="_blank"
                            class="block w-full text-center py-2.5 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 rounded-lg text-sm font-medium transition">
                            Cetak Bukti Peminjaman
                        </a>
                    </div>
                @elseif($peminjaman->status == 'completed')
                    <div class="bg-gray-50 text-gray-600 border border-gray-200 p-4 rounded-lg text-center text-sm">
                        Peminjaman Selesai.
                    </div>
                    <a href="{{ route('staff.peminjaman.bukti', $peminjaman->id) }}" target="_blank"
                        class="block w-full text-center py-2.5 mt-3 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 rounded-lg text-sm font-medium transition">
                        Cetak Bukti Riwayat
                    </a>
                @elseif($peminjaman->status == 'rejected')
                    <div class="bg-white border border-red-100 text-red-600 p-4 rounded-lg text-center text-sm">
                        Status: Ditolak.
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Approve -->
    <div id="approveModal" class="relative z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-gray-500/30 backdrop-blur-sm transition-opacity" onclick="closeApproveModal()"></div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <!-- Modal Panel -->
                <div
                    class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-gray-100 ring-1 ring-black/5">
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                <h3 class="text-lg font-bold leading-6 text-gray-900 mb-4" id="modal-title">Setujui
                                    Peminjaman</h3>

                                <div class="mb-4 bg-gray-50 p-3 rounded-lg border border-gray-100 text-left">
                                    <p class="text-sm text-gray-600">Barang: <span
                                            class="font-bold text-gray-900">{{ $peminjaman->barang->nama_barang }}</span>
                                    </p>
                                    <p class="text-sm text-gray-600">Jumlah Diminta: <span
                                            class="font-bold text-gray-900">{{ $peminjaman->jumlah }} Unit</span></p>
                                </div>

                                <form id="approveForm" method="POST"
                                    action="{{ route('staff.peminjaman.approve', $peminjaman->id) }}">
                                    @csrf
                                    <div class="mb-4 text-left">
                                        <label class="block text-sm font-medium mb-2 text-gray-700">Pilih Unit (Pilih
                                            {{ $peminjaman->jumlah }})</label>
                                        <div id="unitInputsContainer" class="space-y-3 max-h-[60vh] overflow-y-auto pr-1">
                                            <!-- Unit selects will be inserted here -->
                                        </div>
                                    </div>

                                    <div
                                        class="bg-gray-50 -mx-6 -mb-6 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 mt-6 border-t border-gray-100">
                                        <button type="submit"
                                            class="inline-flex w-full justify-center rounded-lg bg-black px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-800 sm:ml-3 sm:w-auto transition-all">
                                            Simpan & Setujui
                                        </button>
                                        <button type="button" onclick="closeApproveModal()"
                                            class="mt-3 inline-flex w-full justify-center rounded-lg bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition-all">
                                            Batal
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Reject -->
    <div id="rejectModal" class="relative z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-gray-500/30 backdrop-blur-sm transition-opacity" onclick="closeRejectModal()"></div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div
                    class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-gray-100 ring-1 ring-black/5">
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                <h3 class="text-lg font-bold leading-6 text-gray-900 mb-4">Tolak Peminjaman</h3>
                                <form id="rejectForm" method="POST" action="">
                                    @csrf
                                    <div class="mb-4 text-left">
                                        <label class="block text-sm font-medium mb-1 text-gray-700">Alasan Penolakan</label>
                                        <textarea name="keterangan_penolakan"
                                            class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-black focus:border-black shadow-sm"
                                            rows="3" placeholder="Tulis alasan penolakan..." required></textarea>
                                    </div>
                                    <div
                                        class="bg-gray-50 -mx-6 -mb-6 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 mt-6 border-t border-gray-100">
                                        <button type="submit"
                                            class="inline-flex w-full justify-center rounded-lg bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto transition-all">
                                            Tolak Pengajuan
                                        </button>
                                        <button type="button" onclick="closeRejectModal()"
                                            class="mt-3 inline-flex w-full justify-center rounded-lg bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition-all">
                                            Batal
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Data from Controller
        const loanQty = {{ $peminjaman->jumlah }};
        const userSelectedUnitId = {{ $peminjaman->barang_unit_id ?? 'null' }};

        // Available units filtered by ACTIVE status only
        const availableUnits = @json($peminjaman->barang->units->where('status', 'aktif')->map(function ($u) {
            return ['id' => $u->id, 'kode' => $u->kode_unit];
        })->values());

        function openApproveModal() {
            const container = document.getElementById('unitInputsContainer');
            container.innerHTML = ''; // Clear previous

            // Validation: Check checking stocks
            if (availableUnits.length === 0) {
                container.innerHTML = `
                                <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-lg mb-4">
                                    <p class="font-bold flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                        Stok Kosong!
                                    </p>
                                    <p class="text-sm mt-1">Tidak ada unit yang tersedia untuk dipinjam.</p>
                                </div>`;
                toggleSubmit(false);
                document.getElementById('approveModal').classList.remove('hidden');
                return;
            }

            // Scenario 1: Loan Quantity = 1
            if (loanQty === 1) {
                // Determine which unit to select
                // Priority: 1. User Selected Unit (if valid/avail), 2. First Available Unit
                let selectedUnit = null;

                if (userSelectedUnitId) {
                    selectedUnit = availableUnits.find(u => u.id == userSelectedUnitId);
                }

                if (!selectedUnit) {
                    selectedUnit = availableUnits[0];
                }

                container.innerHTML = `
                        <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                            <label class="block text-xs font-bold text-gray-500 mb-1">Unit Dipilih (Otomatis)</label>
                            <div class="flex items-center gap-2">
                                 <input type="hidden" name="barang_unit_ids[]" value="${selectedUnit.id}">
                                 <input type="text" value="${selectedUnit.kode}" readonly 
                                    class="w-full bg-white border border-gray-300 rounded-md p-2 text-sm text-gray-700 font-mono focus:outline-none shadow-sm">
                            </div>
                        </div>
                    `;
                toggleSubmit(true);
            }
            // Scenario 2: Loan Quantity > 1 (Multiple Items)
            else {
                let checkboxes = '';
                availableUnits.forEach((unit) => {
                    checkboxes += `
                                <label class="flex items-center gap-3 p-3 bg-gray-50 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-100 transition">
                                    <input type="checkbox" name="barang_unit_ids[]" value="${unit.id}" 
                                        class="w-4 h-4 text-black border-gray-300 rounded focus:ring-black">
                                    <span class="text-sm font-medium text-gray-700 font-mono">${unit.kode}</span>
                                </label>
                            `;
                });

                container.innerHTML = `
                            <div class="bg-yellow-50 p-3 rounded-lg border border-yellow-200 mb-4 text-xs text-yellow-800">
                                <p class="font-bold">Info Approval Parsial:</p>
                                <ul class="list-disc list-inside mt-1">
                                    <li>Pilih unit yang ingin disetujui.</li>
                                    <li>Unit yang <strong>tidak dipilih</strong> akan otomatis ditolak.</li>
                                    <li>Maksimal pilih <strong>${loanQty}</strong> unit.</li>
                                </ul>
                            </div>
                            <label class="block text-sm font-medium mb-2">Pilih Unit Tersedia (${availableUnits.length} Available)</label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-60 overflow-y-auto">
                                ${checkboxes}
                            </div>
                        `;
                toggleSubmit(true);
            }

            document.getElementById('approveModal').classList.remove('hidden');
        }

        function toggleSubmit(enable) {
            const submitBtn = document.querySelector('#approveForm button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = !enable;
                if (enable) {
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                } else {
                    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                }
            }
        }

        function closeApproveModal() {
            document.getElementById('approveModal').classList.add('hidden');
        }

        // Keep reject modal functions as is
        function openRejectModal(id) {
            document.getElementById('rejectModal').classList.remove('hidden');
            document.getElementById('rejectForm').action = `/staff/peminjaman/${id}/reject`;
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
        }
    </script>
@endsection