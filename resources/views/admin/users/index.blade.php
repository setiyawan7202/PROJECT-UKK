@extends('layouts.admin')

@section('title', 'Kelola User')

@section('content')
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-xl lg:text-2xl font-bold text-gray-900">Kelola User</h1>
            <p class="text-sm text-gray-500">Daftar semua user dalam sistem</p>
        </div>
        <div class="flex gap-2">
            <button onclick="document.getElementById('importModal').classList.remove('hidden')"
                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-green-600 text-white rounded-xl font-medium text-sm hover:bg-green-700 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                </svg>
                Import
            </button>
            <a href="{{ route('admin.users.trash') }}"
                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gray-100 text-gray-700 rounded-xl font-medium text-sm hover:bg-gray-200 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                Sampah
            </a>
            <a href="{{ route('admin.users.create') }}"
                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-black text-white rounded-xl font-medium text-sm hover:bg-gray-800 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Tambah User
            </a>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm">
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">
            {{ $errors->first() }}
        </div>
    @endif

    <!-- Search & Filter -->
    <div class="bg-white rounded-xl lg:rounded-2xl border border-gray-100 p-4 mb-4">
        <form id="searchForm" method="GET" action="{{ route('admin.users.index') }}"
            class="flex flex-col lg:flex-row gap-4">
            <!-- Search -->
            <div class="flex-1">
                <div class="relative">
                    <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" name="search" id="searchInput" value="{{ $search ?? '' }}"
                        placeholder="Cari nama atau email..."
                        class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-black focus:ring-1 focus:ring-black transition">
                </div>
            </div>

            <!-- Filters -->
            <div class="flex flex-wrap gap-2 items-center">
                <a href="{{ route('admin.users.index', ['search' => $search ?? '']) }}"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition {{ !$filter && !$statusFilter && !$kelasFilter ? 'bg-black text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    Semua
                </a>
                @if(Auth::user()->role === 'superadmin')
                    <a href="{{ route('admin.users.index', ['filter' => 'superadmin', 'search' => $search ?? '']) }}"
                        class="px-4 py-2 rounded-lg text-sm font-medium transition {{ $filter === 'superadmin' ? 'bg-black text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                        Super Admin
                    </a>
                @endif
                <a href="{{ route('admin.users.index', ['filter' => 'admin', 'search' => $search ?? '']) }}"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition {{ $filter === 'admin' ? 'bg-black text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    Admin
                </a>
                <a href="{{ route('admin.users.index', ['filter' => 'petugas', 'search' => $search ?? '']) }}"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition {{ $filter === 'petugas' ? 'bg-black text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    Petugas
                </a>
                <a href="{{ route('admin.users.index', ['filter' => 'kepala_lab', 'search' => $search ?? '']) }}"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition {{ $filter === 'kepala_lab' ? 'bg-black text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    Kepala Lab
                </a>
                <a href="{{ route('admin.users.index', ['filter' => 'pengguna', 'search' => $search ?? '']) }}"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition {{ $filter === 'pengguna' ? 'bg-black text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    Pengguna
                </a>
                <span class="border-l border-gray-300 mx-1"></span>
                <a href="{{ route('admin.users.index', ['status' => 'siswa', 'search' => $search ?? '']) }}"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition {{ $statusFilter === 'siswa' ? 'bg-black text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    Siswa
                </a>
                <a href="{{ route('admin.users.index', ['status' => 'guru', 'search' => $search ?? '']) }}"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition {{ $statusFilter === 'guru' ? 'bg-black text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    Guru
                </a>
                <span class="border-l border-gray-300 mx-1"></span>
                <select name="kelas"
                    onchange="window.location.href='{{ route('admin.users.index') }}?kelas=' + this.value + '&search={{ $search ?? '' }}'"
                    class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-black focus:ring-1 focus:ring-black transition bg-white">
                    <option value="">Semua Kelas</option>
                    @foreach($kelasList as $kelas)
                        <option value="{{ $kelas->id }}" {{ $kelasFilter == $kelas->id ? 'selected' : '' }}>
                            {{ $kelas->nama_kelas }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            // Debounced auto-submit for search
            let searchTimeout;
            document.getElementById('searchInput').addEventListener('input', function () {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    document.getElementById('searchForm').submit();
                }, 500);
            });

            // Toggle NIP/NISN column display
            let idColumnMode = 'all'; // 'all', 'nip', 'nisn'
            function toggleIdColumn() {
                const modes = ['all', 'nip', 'nisn'];
                const labels = { 'all': 'NIP/NISN', 'nip': 'NIP', 'nisn': 'NISN' };
                const currentIndex = modes.indexOf(idColumnMode);
                idColumnMode = modes[(currentIndex + 1) % modes.length];

                document.getElementById('idColumnLabel').textContent = labels[idColumnMode];

                // Toggle visibility of rows based on mode
                document.querySelectorAll('[data-id-type]').forEach(cell => {
                    const row = cell.closest('tr');
                    const type = cell.dataset.idType;
                    if (idColumnMode === 'all') {
                        row.style.display = '';
                    } else if (idColumnMode === 'nip' && type !== 'guru') {
                        row.style.display = 'none';
                    } else if (idColumnMode === 'nisn' && type !== 'siswa') {
                        row.style.display = 'none';
                    } else {
                        row.style.display = '';
                    }
                });
            }
        </script>
    @endpush

    <div class="bg-white rounded-xl lg:rounded-2xl border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto w-full">
            <table class="w-full min-w-[1000px] text-left text-sm whitespace-nowrap">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 border-b border-gray-100">
                    <tr>
                        <th
                            class="px-4 lg:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase whitespace-nowrap w-10">
                            No</th>
                        <th
                            class="px-4 lg:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase whitespace-nowrap">
                            Nama</th>
                        <th class="px-4 lg:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase whitespace-nowrap cursor-pointer hover:bg-gray-100 transition"
                            onclick="toggleIdColumn()">
                            <span class="flex items-center gap-1">
                                <span id="idColumnLabel">NIP/NISN</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                </svg>
                            </span>
                        </th>
                        <th
                            class="px-4 lg:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase whitespace-nowrap">
                            Email</th>
                        <th
                            class="px-4 lg:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase whitespace-nowrap">
                            Role</th>
                        <th
                            class="px-4 lg:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase whitespace-nowrap">
                            Status</th>
                        <th
                            class="px-4 lg:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase whitespace-nowrap">
                            Kelas</th>
                        <th
                            class="px-4 lg:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase whitespace-nowrap">
                            No. HP</th>
                        <th
                            class="px-4 lg:px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase whitespace-nowrap">
                            Aktivasi</th>
                        <th
                            class="px-4 lg:px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase whitespace-nowrap">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 lg:px-6 py-4">
                                <span class="text-sm text-gray-600 font-medium">{{ $loop->iteration }}</span>
                            </td>
                            <td class="px-4 lg:px-6 py-4">
                                <div>
                                    <p class="font-medium text-gray-900 text-sm whitespace-nowrap">
                                        @if($user->status == 'siswa' && $user->siswa)
                                            {{ $user->siswa->username }}
                                        @elseif($user->status == 'guru' && $user->guru)
                                            {{ $user->guru->username }}
                                        @else
                                            {{ $user->nama_lengkap ?? $user->email }}
                                        @endif
                                    </p>
                                </div>
                            </td>
                            <td class="px-4 lg:px-6 py-4" data-id-type="{{ $user->status }}">
                                <span class="text-sm text-gray-600 font-mono">{{ $user->data_nip_nisn ?? '-' }}</span>
                            </td>
                            <td class="px-4 lg:px-6 py-4">
                                <span class="text-sm text-gray-600 whitespace-nowrap">{{ $user->email }}</span>
                            </td>
                            <td class="px-4 lg:px-6 py-4">
                                <span
                                    class="inline-flex px-2.5 py-1 text-xs font-medium rounded-full
                                                                                                                                                        @if($user->role === 'superadmin') bg-purple-600 text-white
                                                                                                                                                        @elseif($user->role === 'admin') bg-black text-white
                                                                                                                                                        @elseif($user->role === 'petugas') bg-gray-700 text-white
                                                                                                                                                        @elseif($user->role === 'kepala_lab') bg-amber-500 text-white
                                                                                                                                                        @else bg-gray-100 text-gray-700
                                                                                                                                                        @endif">
                                    @if($user->role === 'superadmin') Super Admin
                                    @elseif($user->role === 'kepala_lab') Kepala Lab
                                    @else {{ ucfirst($user->role) }}
                                    @endif
                                </span>
                            </td>
                            <td class="px-4 lg:px-6 py-4">
                                @if($user->status)
                                    <span
                                        class="inline-flex px-2.5 py-1 text-xs font-medium rounded-full
                                                                                                                                                                                                                                                                                                                                                                @if($user->status === 'siswa') bg-blue-100 text-blue-700
                                                                                                                                                                                                                                                                                                                                                                @else bg-green-100 text-green-700
                                                                                                                                                                                                                                                                                                                                                                @endif">
                                        {{ ucfirst($user->status) }}
                                    </span>
                                @else
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 lg:px-6 py-4">
                                @if($user->status == 'siswa' && $user->siswa && $user->siswa->kelas)
                                    <span class="text-sm text-gray-600">{{ $user->siswa->kelas->nama_kelas }}</span>
                                @else
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 lg:px-6 py-4">
                                @if($user->status == 'siswa' && $user->siswa)
                                    <span class="text-sm text-gray-600">{{ $user->siswa->no_hp ?? '-' }}</span>
                                @elseif($user->status == 'guru' && $user->guru)
                                    <span class="text-sm text-gray-600">{{ $user->guru->no_hp ?? '-' }}</span>
                                @else
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 lg:px-6 py-4 text-center">
                                @if($user->is_active)
                                    <span
                                        class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 lg:px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @if($user->id === auth()->id() || Auth::user()->role === 'superadmin' || !in_array($user->role, ['superadmin', 'admin']))
                                        <a href="{{ route('admin.users.edit', $user->id) }}"
                                            class="p-2 text-gray-500 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                    @endif
                                    @if($user->id !== auth()->id() && (Auth::user()->role === 'superadmin' || !in_array($user->role, ['superadmin', 'admin'])))
                                        <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}"
                                            onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-6 py-12 text-center text-gray-500">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197" />
                                </svg>
                                <p>Belum ada user</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Import Modal -->
    <div id="importModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"
                onclick="document.getElementById('importModal').classList.add('hidden')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                        Import Data User
                    </h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500">
                            Silakan upload file Excel (.xlsx) dengan data siswa atau guru.
                            <br>
                            <a href="{{ route('admin.users.template') }}"
                                class="text-blue-600 hover:text-blue-800 underline flex items-center gap-1 mt-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                Download Template
                            </a>
                        </p>
                        <form action="{{ route('admin.users.import') }}" method="POST" enctype="multipart/form-data"
                            class="mt-4">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tipe User</label>
                                <select name="type"
                                    class="w-full px-3 py-2 border rounded-lg focus:ring-black focus:border-black">
                                    <option value="siswa">Siswa</option>
                                    <option value="guru">Guru</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">File Excel</label>
                                <input type="file" name="file" accept=".xlsx,.xls,.csv"
                                    class="w-full p-2 border border-gray-300 rounded-lg" required>
                            </div>
                            <div class="mt-5 sm:mt-6 flex gap-2">
                                <button type="submit"
                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-black text-base font-medium text-white hover:bg-gray-800 focus:outline-none sm:text-sm">
                                    Import
                                </button>
                                <button type="button"
                                    onclick="document.getElementById('importModal').classList.add('hidden')"
                                    class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:text-sm">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection