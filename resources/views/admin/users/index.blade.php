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
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col lg:flex-row gap-4">
            <!-- Search -->
            <div class="flex-1">
                <div class="relative">
                    <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama atau email..."
                        class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-black focus:ring-1 focus:ring-black transition">
                </div>
            </div>

            <!-- Filters -->
            <div class="flex flex-wrap gap-2 items-center">
                <a href="{{ route('admin.users.index', ['search' => $search ?? '']) }}"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition {{ !$filter && !$statusFilter && !$kelasFilter ? 'bg-black text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    Semua
                </a>
                <a href="{{ route('admin.users.index', ['filter' => 'admin', 'search' => $search ?? '']) }}"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition {{ $filter === 'admin' ? 'bg-black text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    Admin
                </a>
                <a href="{{ route('admin.users.index', ['filter' => 'petugas', 'search' => $search ?? '']) }}"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition {{ $filter === 'petugas' ? 'bg-black text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    Petugas
                </a>
                <a href="{{ route('admin.users.index', ['filter' => 'pengguna', 'search' => $search ?? '']) }}"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition {{ $filter === 'pengguna' ? 'bg-black text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    Pengguna
                </a>
                <span class="border-l border-gray-300 mx-1"></span>
                <a href="{{ route('admin.users.index', ['status' => 'siswa', 'search' => $search ?? '']) }}"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition {{ $statusFilter === 'siswa' ? 'bg-blue-600 text-white' : 'bg-blue-50 text-blue-600 hover:bg-blue-100' }}">
                    Siswa
                </a>
                <a href="{{ route('admin.users.index', ['status' => 'guru', 'search' => $search ?? '']) }}"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition {{ $statusFilter === 'guru' ? 'bg-green-600 text-white' : 'bg-green-50 text-green-600 hover:bg-green-100' }}">
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
                <button type="submit"
                    class="px-4 py-2 bg-black text-white rounded-lg text-sm font-medium hover:bg-gray-800 transition">
                    Cari
                </button>
            </div>
        </form>
    </div>

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
                            <td class="px-4 lg:px-6 py-4">
                                <span class="text-sm text-gray-600 whitespace-nowrap">{{ $user->email }}</span>
                            </td>
                            <td class="px-4 lg:px-6 py-4">
                                <span
                                    class="inline-flex px-2.5 py-1 text-xs font-medium rounded-full
                                                                                                                        @if($user->role === 'admin') bg-black text-white
                                                                                                                        @elseif($user->role === 'petugas') bg-gray-700 text-white
                                                                                                                        @else bg-gray-100 text-gray-700
                                                                                                                        @endif">
                                    {{ ucfirst($user->role) }}
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
                            <td class="px-4 lg:px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.users.edit', $user->id) }}"
                                        class="p-2 text-gray-500 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    @if($user->id !== auth()->id())
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
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
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
@endsection