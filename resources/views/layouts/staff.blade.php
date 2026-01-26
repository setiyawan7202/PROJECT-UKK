<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Staff Panel') - SIAPRAS</title>
    <link rel="icon" type="image/png" href="{{ Vite::asset('resources/img/logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * {
            font-family: 'Inter', sans-serif;
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

<body class="min-h-screen bg-gray-50 overflow-hidden h-screen w-screen">

    <div id="sidebar-overlay"
        class="sidebar-overlay fixed inset-0 bg-black/50 z-40 opacity-0 pointer-events-none lg:hidden"></div>

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
            <span class="px-3 py-1 bg-gray-700 text-white text-xs font-medium rounded-full">Petugas</span>
        </div>
    </header>

    <div class="flex h-full">
        <aside id="sidebar"
            class="sidebar w-64 min-h-screen bg-white border-r border-gray-100 fixed left-0 top-0 z-50 lg:translate-x-0">
            <div class="p-4 lg:p-6 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <img src="{{ Vite::asset('resources/img/logo.png') }}" alt="Logo"
                            class="w-8 h-8 lg:w-10 lg:h-10 object-contain">
                        <div>
                            <span class="text-base lg:text-lg font-bold">SIAPRAS</span>
                            <p class="text-xs text-gray-500 hidden lg:block">Panel Staff</p>
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

            <nav class="p-3 lg:p-4 space-y-1 overflow-y-auto" style="max-height: calc(100vh - 180px);">
                <a href="{{ route('staff.index') }}"
                    class="sidebar-link {{ request()->routeIs('staff.index') ? 'active' : 'text-gray-600' }} flex items-center gap-3 px-3 lg:px-4 py-2.5 lg:py-3 rounded-xl font-medium text-sm lg:text-base">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>

                <p class="px-3 pt-4 pb-2 text-xs font-semibold text-gray-400 uppercase">Data Master</p>

                <a href="{{ route('staff.kategori.index') }}"
                    class="sidebar-link {{ request()->routeIs('staff.kategori.*') ? 'active' : 'text-gray-600' }} flex items-center gap-3 px-3 lg:px-4 py-2.5 lg:py-3 rounded-xl font-medium text-sm lg:text-base">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                    Kategori
                </a>

                <a href="{{ route('staff.ruangan.index') }}"
                    class="sidebar-link {{ request()->routeIs('staff.ruangan.*') ? 'active' : 'text-gray-600' }} flex items-center gap-3 px-3 lg:px-4 py-2.5 lg:py-3 rounded-xl font-medium text-sm lg:text-base">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    Ruangan
                </a>

                <p class="px-3 pt-4 pb-2 text-xs font-semibold text-gray-400 uppercase">Inventaris</p>

                <a href="{{ route('staff.barang.index') }}"
                    class="sidebar-link {{ request()->routeIs('staff.barang.*') ? 'active' : 'text-gray-600' }} flex items-center gap-3 px-3 lg:px-4 py-2.5 lg:py-3 rounded-xl font-medium text-sm lg:text-base">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    Data Barang
                </a>
            </nav>

            <div class="absolute bottom-0 left-0 right-0 p-3 lg:p-4 border-t border-gray-100 bg-white">
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

        <main class="flex-1 lg:ml-64 h-full overflow-y-auto p-4 lg:p-8 pt-20 lg:pt-8 bg-gray-50">
            @yield('content')
        </main>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const menuToggle = document.getElementById('menu-toggle');
        const closeSidebar = document.getElementById('close-sidebar');

        menuToggle?.addEventListener('click', () => {
            sidebar.classList.add('open');
            overlay.classList.add('open');
            document.body.style.overflow = 'hidden';
        });

        [closeSidebar, overlay].forEach(el => {
            el?.addEventListener('click', () => {
                sidebar.classList.remove('open');
                overlay.classList.remove('open');
                document.body.style.overflow = '';
            });
        });
    </script>

    @stack('scripts')
</body>

</html>