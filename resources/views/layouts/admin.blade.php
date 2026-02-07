<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Dashboard') - SIAPRAS</title>
    <link rel="icon" type="image/png" href="{{ Vite::asset('resources/img/logo.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <style>
        .select2-container .select2-selection--single {
            height: 48px !important;
            border: 1px solid #e5e7eb !important;
            border-radius: 0.75rem !important;
            padding: 0.5rem 0.5rem !important;
            display: flex !important;
            align-items: center !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 46px !important;
            right: 10px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: normal !important;
            padding-left: 10px !important;
            color: #111827 !important;
        }

        .select2-dropdown {
            border: 1px solid #e5e7eb !important;
            border-radius: 0.75rem !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            overflow: hidden !important;
        }

        .select2-search__field {
            border-radius: 0.5rem !important;
            padding: 0.5rem !important;
        }

        select.searchable-select {
            visibility: hidden;
        }

        .sidebar {
            transition: transform 0.3s ease;
        }

        .sidebar-overlay {
            transition: opacity 0.3s ease;
        }

        @media (max-width: 1023px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .sidebar-overlay.open {
                opacity: 1;
                pointer-events: auto;
            }
        }

        .sidebar-link {
            transition: all 0.2s ease;
        }

        .sidebar-link:hover {
            background: #f3f4f6;
        }

        .sidebar-link.active {
            background: #000;
            color: #fff;
        }
    </style>
</head>

<body class="h-screen bg-gray-50 overflow-hidden">

    <!-- Mobile Overlay -->
    <div id="sidebar-overlay"
        class="sidebar-overlay fixed inset-0 bg-black/50 z-40 opacity-0 pointer-events-none lg:hidden"></div>

    <!-- Mobile Header -->
    <header class="lg:hidden fixed top-0 left-0 right-0 bg-white border-b border-gray-100 z-30 px-4 py-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <button id="menu-toggle" class="p-2 hover:bg-gray-100 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <span class="text-lg font-bold">SIAPRAS</span>
            </div>
            <span class="px-3 py-1 bg-black text-white text-xs font-medium rounded-full">
                {{ Auth::user()->role === 'superadmin' ? 'Super Admin' : 'Admin' }}
            </span>
        </div>
    </header>

    <div class="flex h-full">
        <!-- Sidebar -->
        <aside id="sidebar"
            class="sidebar w-64 h-screen bg-white border-r border-gray-100 fixed left-0 top-0 z-50 lg:translate-x-0 flex flex-col">
            <!-- Logo -->
            <div class="p-4 lg:p-6 border-b border-gray-100 shrink-0">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <img src="{{ Vite::asset('resources/img/logo.png') }}" alt="Logo"
                            class="w-8 h-8 lg:w-10 lg:h-10 object-contain">
                        <div>
                            <span class="text-base lg:text-lg font-bold">SIAPRAS</span>
                            <p class="text-xs text-gray-500 hidden lg:block">
                                {{ Auth::user()->role === 'superadmin' ? 'Super Admin Panel' : 'Admin Panel' }}
                            </p>
                        </div>
                    </div>
                    <button id="close-sidebar" class="lg:hidden p-2 hover:bg-gray-100 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Menu -->
            <nav class="p-3 lg:p-4 space-y-1 overflow-y-auto" style="max-height: calc(100vh - 180px);">
                <a href="{{ route('admin.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.index') ? 'active' : 'text-gray-600' }} flex items-center gap-3 px-3 lg:px-4 py-2.5 lg:py-3 rounded-xl font-medium text-sm lg:text-base">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>

                <!-- Data Master Dropdown -->
                <div class="sidebar-dropdown">
                    <button onclick="toggleDropdown('dataMasterMenu')"
                        class="w-full flex items-center justify-between px-3 lg:px-4 py-2.5 lg:py-3 rounded-xl font-medium text-sm lg:text-base text-gray-600 hover:bg-gray-50 transition {{ request()->routeIs('admin.kategori.*') || request()->routeIs('admin.ruangan.*') || request()->routeIs('admin.kelas.*') ? 'bg-gray-100' : '' }}">
                        <span class="flex items-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                            </svg>
                            Data Master
                        </span>
                        <svg class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div id="dataMasterMenu"
                        class="pl-6 space-y-1 mt-1 {{ request()->routeIs('admin.kategori.*') || request()->routeIs('admin.ruangan.*') || request()->routeIs('admin.kelas.*') ? '' : 'hidden' }}">
                        <a href="{{ route('admin.kategori.index') }}"
                            class="sidebar-link {{ request()->routeIs('admin.kategori.*') ? 'active' : 'text-gray-600' }} flex items-center gap-3 px-3 py-2 rounded-lg text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                            Kategori
                        </a>
                        <a href="{{ route('admin.ruangan.index') }}"
                            class="sidebar-link {{ request()->routeIs('admin.ruangan.*') ? 'active' : 'text-gray-600' }} flex items-center gap-3 px-3 py-2 rounded-lg text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            Ruangan
                        </a>
                        <a href="{{ route('admin.kelas.index') }}"
                            class="sidebar-link {{ request()->routeIs('admin.kelas.*') ? 'active' : 'text-gray-600' }} flex items-center gap-3 px-3 py-2 rounded-lg text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            Kelas
                        </a>
                    </div>
                </div>

                <!-- Inventaris Dropdown -->
                <div class="sidebar-dropdown">
                    <button onclick="toggleDropdown('inventarisMenu')"
                        class="w-full flex items-center justify-between px-3 lg:px-4 py-2.5 lg:py-3 rounded-xl font-medium text-sm lg:text-base text-gray-600 hover:bg-gray-50 transition {{ request()->routeIs('admin.barang.*') || request()->routeIs('admin.checklist-templates.*') ? 'bg-gray-100' : '' }}">
                        <span class="flex items-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            Inventaris
                        </span>
                        <svg class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div id="inventarisMenu"
                        class="pl-6 space-y-1 mt-1 {{ request()->routeIs('admin.barang.*') || request()->routeIs('admin.checklist-templates.*') ? '' : 'hidden' }}">
                        <a href="{{ route('admin.barang.index') }}"
                            class="sidebar-link {{ request()->routeIs('admin.barang.*') ? 'active' : 'text-gray-600' }} flex items-center gap-3 px-3 py-2 rounded-lg text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            Data Aset
                        </a>
                        <a href="{{ route('admin.checklist-templates.index') }}"
                            class="sidebar-link {{ request()->routeIs('admin.checklist-templates.*') ? 'active' : 'text-gray-600' }} flex items-center gap-3 px-3 py-2 rounded-lg text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                            Template Checklist
                        </a>
                    </div>
                </div>

                <!-- Maintenance Dropdown -->
                <div class="sidebar-dropdown">
                    <button onclick="toggleDropdown('maintenanceMenu')"
                        class="w-full flex items-center justify-between px-3 lg:px-4 py-2.5 lg:py-3 rounded-xl font-medium text-sm lg:text-base text-gray-600 hover:bg-gray-50 transition {{ request()->routeIs('admin.maintenance.*') ? 'bg-gray-100' : '' }}">
                        <span class="flex items-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Maintenance
                        </span>
                        <svg class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div id="maintenanceMenu"
                        class="pl-6 space-y-1 mt-1 {{ request()->routeIs('admin.maintenance.*') ? '' : 'hidden' }}">
                        <a href="{{ route('admin.maintenance.index') }}"
                            class="sidebar-link {{ request()->routeIs('admin.maintenance.index') || request()->routeIs('admin.maintenance.create') ? 'active' : 'text-gray-600' }} flex items-center gap-3 px-3 py-2 rounded-lg text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Jadwal
                        </a>
                        <a href="{{ route('admin.maintenance.logs') }}"
                            class="sidebar-link {{ request()->routeIs('admin.maintenance.logs') || request()->routeIs('admin.maintenance.createLog') ? 'active' : 'text-gray-600' }} flex items-center gap-3 px-3 py-2 rounded-lg text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Log
                        </a>
                    </div>
                </div>

                <!-- Peminjaman Dropdown -->
                <div class="sidebar-dropdown">
                    <button onclick="toggleDropdown('peminjamanMenu')"
                        class="w-full flex items-center justify-between px-3 lg:px-4 py-2.5 lg:py-3 rounded-xl font-medium text-sm lg:text-base text-gray-600 hover:bg-gray-50 transition {{ request()->routeIs('admin.peminjaman.*') || request()->routeIs('admin.scan.*') ? 'bg-gray-100' : '' }}">
                        <span class="flex items-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Peminjaman
                        </span>
                        <svg class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div id="peminjamanMenu"
                        class="pl-6 space-y-1 mt-1 {{ request()->routeIs('admin.peminjaman.*') || request()->routeIs('admin.scan.*') ? '' : 'hidden' }}">
                        <a href="{{ route('admin.scan.index') }}"
                            class="sidebar-link {{ request()->routeIs('admin.scan.*') ? 'active' : 'text-gray-600' }} flex items-center gap-3 px-3 py-2 rounded-lg text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                            </svg>
                            Scan QR
                        </a>
                        <a href="{{ route('admin.peminjaman.index') }}"
                            class="sidebar-link {{ request()->routeIs('admin.peminjaman.*') ? 'active' : 'text-gray-600' }} flex items-center gap-3 px-3 py-2 rounded-lg text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Daftar Peminjaman
                        </a>
                    </div>
                </div>

                <!-- Pengaduan (Single item - no dropdown needed) -->
                <a href="{{ route('admin.pengaduan.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.pengaduan.*') ? 'active' : 'text-gray-600' }} flex items-center gap-3 px-3 lg:px-4 py-2.5 lg:py-3 rounded-xl font-medium text-sm lg:text-base">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    Pengaduan
                </a>

                <!-- Pengguna & Lainnya Dropdown -->
                <div class="sidebar-dropdown">
                    <button onclick="toggleDropdown('penggunaMenu')"
                        class="w-full flex items-center justify-between px-3 lg:px-4 py-2.5 lg:py-3 rounded-xl font-medium text-sm lg:text-base text-gray-600 hover:bg-gray-50 transition {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.laporan.*') ? 'bg-gray-100' : '' }}">
                        <span class="flex items-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            Pengguna & Lainnya
                        </span>
                        <svg class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div id="penggunaMenu"
                        class="pl-6 space-y-1 mt-1 {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.laporan.*') ? '' : 'hidden' }}">
                        <a href="{{ route('admin.users.index') }}"
                            class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : 'text-gray-600' }} flex items-center gap-3 px-3 py-2 rounded-lg text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            Kelola User
                        </a>
                        <a href="{{ route('admin.laporan.index') }}"
                            class="sidebar-link {{ request()->routeIs('admin.laporan.index') ? 'active' : 'text-gray-600' }} flex items-center gap-3 px-3 py-2 rounded-lg text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Laporan
                        </a>
                        <a href="{{ route('admin.laporan.assetHealth') }}"
                            class="sidebar-link {{ request()->routeIs('admin.laporan.assetHealth') ? 'active' : 'text-gray-600' }} flex items-center gap-3 px-3 py-2 rounded-lg text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                            Kesehatan Aset
                        </a>
                        <a href="{{ route('admin.laporan.analytics') }}"
                            class="sidebar-link {{ request()->routeIs('admin.laporan.analytics') ? 'active' : 'text-gray-600' }} flex items-center gap-3 px-3 py-2 rounded-lg text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            Analisis & Lokasi
                        </a>
                        <a href="{{ route('admin.activity.index') }}"
                            class="sidebar-link {{ request()->routeIs('admin.activity.*') ? 'active' : 'text-gray-600' }} flex items-center gap-3 px-3 py-2 rounded-lg text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Activity Log
                        </a>
                    </div>
                </div>
            </nav>

            <!-- Logout -->
            <div class="p-3 lg:p-4 border-t border-gray-100 bg-white shrink-0">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-3 px-3 lg:px-4 py-2.5 lg:py-3 text-red-600 hover:bg-red-50 rounded-xl font-medium text-sm lg:text-base transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 lg:ml-64 h-full overflow-y-auto p-4 lg:p-8 pt-20 lg:pt-8 bg-gray-50">
            @yield('content')
        </main>
    </div>

    <script>
        $(document).ready(function () {
            $('.searchable-select').select2({
                placeholder: "Pilih opsi...",
                allowClear: true,
                width: '100%'
            });
        });
    </script>
    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const menuToggle = document.getElementById('menu-toggle');
        const closeSidebar = document.getElementById('close-sidebar');

        function openSidebar() {
            sidebar.classList.add('open');
            overlay.classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebarFn() {
            sidebar.classList.remove('open');
            overlay.classList.remove('open');
            document.body.style.overflow = '';
        }

        menuToggle?.addEventListener('click', openSidebar);
        closeSidebar?.addEventListener('click', closeSidebarFn);
        overlay?.addEventListener('click', closeSidebarFn);

        // Sidebar dropdown toggle function
        function toggleDropdown(menuId) {
            const menu = document.getElementById(menuId);
            if (menu) {
                menu.classList.toggle('hidden');
            }
        }
    </script>
    @stack('scripts')
</body>

</html>