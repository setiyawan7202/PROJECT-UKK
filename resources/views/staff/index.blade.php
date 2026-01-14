@extends('layouts.staff')

@section('title', 'Dashboard Petugas')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6 lg:mb-8">
        <div>
            <h1 class="text-xl lg:text-2xl font-bold text-gray-900">Dashboard Staff</h1>
            <p class="text-sm lg:text-base text-gray-500">Selamat datang,
                {{ Auth::user()->nama_lengkap ?? Auth::user()->email }}
            </p>
        </div>
        <span class="hidden lg:inline-flex px-3 py-1 bg-gray-700 text-white text-sm font-medium rounded-full">Staff</span>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-3 gap-3 lg:gap-6 mb-6 lg:mb-8">
        <div class="bg-white rounded-xl lg:rounded-2xl border border-gray-100 p-4 lg:p-6">
            <div class="flex flex-col lg:flex-row lg:items-center gap-2 lg:gap-4">
                <div
                    class="w-10 h-10 lg:w-12 lg:h-12 bg-gray-100 rounded-lg lg:rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 lg:w-6 lg:h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <div>
                    <p class="text-xl lg:text-2xl font-bold">{{ \App\Models\Barang::count() }}</p>
                    <p class="text-xs lg:text-sm text-gray-500">Total Barang</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl lg:rounded-2xl border border-gray-100 p-4 lg:p-6">
            <div class="flex flex-col lg:flex-row lg:items-center gap-2 lg:gap-4">
                <div
                    class="w-10 h-10 lg:w-12 lg:h-12 bg-gray-100 rounded-lg lg:rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 lg:w-6 lg:h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xl lg:text-2xl font-bold">{{ \App\Models\Kategori::count() }}</p>
                    <p class="text-xs lg:text-sm text-gray-500">Kategori</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl lg:rounded-2xl border border-gray-100 p-4 lg:p-6">
            <div class="flex flex-col lg:flex-row lg:items-center gap-2 lg:gap-4">
                <div
                    class="w-10 h-10 lg:w-12 lg:h-12 bg-gray-100 rounded-lg lg:rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 lg:w-6 lg:h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div>
                    <p class="text-xl lg:text-2xl font-bold">{{ \App\Models\Ruangan::count() }}</p>
                    <p class="text-xs lg:text-sm text-gray-500">Ruangan</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 lg:gap-6 mb-6 lg:mb-8">
        <a href="{{ route('staff.barang.create') }}"
            class="bg-white rounded-xl lg:rounded-2xl border border-gray-100 p-4 lg:p-6 hover:shadow-lg transition block">
            <div
                class="w-10 h-10 lg:w-12 lg:h-12 bg-gray-100 rounded-lg lg:rounded-xl flex items-center justify-center mb-3 lg:mb-4">
                <svg class="w-5 h-5 lg:w-6 lg:h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
            </div>
            <h3 class="font-semibold text-gray-900 text-sm lg:text-base mb-1">Tambah Barang</h3>
            <p class="text-xs lg:text-sm text-gray-500">Tambahkan barang baru</p>
        </a>

        <a href="{{ route('staff.kategori.index') }}"
            class="bg-white rounded-xl lg:rounded-2xl border border-gray-100 p-4 lg:p-6 hover:shadow-lg transition block">
            <div
                class="w-10 h-10 lg:w-12 lg:h-12 bg-gray-100 rounded-lg lg:rounded-xl flex items-center justify-center mb-3 lg:mb-4">
                <svg class="w-5 h-5 lg:w-6 lg:h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                </svg>
            </div>
            <h3 class="font-semibold text-gray-900 text-sm lg:text-base mb-1">Kelola Kategori</h3>
            <p class="text-xs lg:text-sm text-gray-500">Atur kategori barang</p>
        </a>

        <a href="{{ route('staff.ruangan.index') }}"
            class="bg-white rounded-xl lg:rounded-2xl border border-gray-100 p-4 lg:p-6 hover:shadow-lg transition block">
            <div
                class="w-10 h-10 lg:w-12 lg:h-12 bg-gray-100 rounded-lg lg:rounded-xl flex items-center justify-center mb-3 lg:mb-4">
                <svg class="w-5 h-5 lg:w-6 lg:h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <h3 class="font-semibold text-gray-900 text-sm lg:text-base mb-1">Kelola Ruangan</h3>
            <p class="text-xs lg:text-sm text-gray-500">Atur data ruangan</p>
        </a>
    </div>
@endsection