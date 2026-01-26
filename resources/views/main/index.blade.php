<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Dashboard - SIAPRAS</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeIn 0.6s ease-out forwards;
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body class="min-h-screen bg-gray-50">

    <!-- Navbar -->
    <nav class="bg-white border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-14 sm:h-16">
                <!-- Logo -->
                <div class="flex items-center gap-2 sm:gap-3">
                    <img src="{{ Vite::asset('resources/img/logo.png') }}" alt="Logo"
                        class="w-8 h-8 sm:w-10 sm:h-10 object-contain">
                    <span class="text-lg sm:text-xl font-bold">SIAPRAS</span>
                </div>

                <!-- User Menu -->
                <div class="flex items-center gap-2 sm:gap-4">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-medium text-gray-900 truncate max-w-[150px]">
                            {{ Auth::user()->nama_lengkap ?? Auth::user()->email }}
                        </p>
                        <p class="text-xs text-gray-500 capitalize">{{ Auth::user()->role }}</p>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-8">

        <!-- Welcome Banner -->
        <div class="bg-black text-white rounded-xl sm:rounded-2xl p-4 sm:p-8 mb-4 sm:mb-8 fade-in">
            <h1 class="text-lg sm:text-2xl md:text-3xl font-bold mb-1 sm:mb-2">
                Selamat Datang,
                {{ Auth::user()->nama_lengkap ?? Auth::user()->email }}!
            </h1>
            <p class="text-gray-300 text-sm sm:text-base">
                Kelola peminjaman sarana dan prasarana sekolah dengan mudah.
            </p>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-3 gap-2 sm:gap-4 lg:gap-6 mb-4 sm:mb-8">
            <!-- Stat 1 -->
            <div class="bg-white rounded-xl sm:rounded-2xl border border-gray-100 p-3 sm:p-6 card-hover fade-in">
                <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4">
                    <div
                        class="w-8 h-8 sm:w-12 sm:h-12 bg-gray-100 rounded-lg sm:rounded-xl flex items-center justify-center">
                        <svg class="w-4 h-4 sm:w-6 sm:h-6 text-gray-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-lg sm:text-2xl font-bold text-gray-900">{{ $stats['active'] }}</p>
                        <p class="text-[10px] sm:text-sm text-gray-500">Aktif</p>
                    </div>
                </div>
            </div>

            <!-- Stat 2 -->
            <div class="bg-white rounded-xl sm:rounded-2xl border border-gray-100 p-3 sm:p-6 card-hover fade-in">
                <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4">
                    <div
                        class="w-8 h-8 sm:w-12 sm:h-12 bg-gray-100 rounded-lg sm:rounded-xl flex items-center justify-center">
                        <svg class="w-4 h-4 sm:w-6 sm:h-6 text-gray-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-lg sm:text-2xl font-bold text-gray-900">{{ $stats['pending'] }}</p>
                        <p class="text-[10px] sm:text-sm text-gray-500">Pending</p>
                    </div>
                </div>
            </div>

            <!-- Stat 3 -->
            <div class="bg-white rounded-xl sm:rounded-2xl border border-gray-100 p-3 sm:p-6 card-hover fade-in">
                <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4">
                    <div
                        class="w-8 h-8 sm:w-12 sm:h-12 bg-gray-100 rounded-lg sm:rounded-xl flex items-center justify-center">
                        <svg class="w-4 h-4 sm:w-6 sm:h-6 text-gray-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-lg sm:text-2xl font-bold text-gray-900">{{ $stats['completed'] }}</p>
                        <p class="text-[10px] sm:text-sm text-gray-500">Selesai</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <h2 class="text-base sm:text-lg font-bold text-gray-900 mb-3 sm:mb-4">Menu Cepat</h2>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-4">
            <!-- Action 1 -->
            <a href="{{ route('peminjaman.create') }}"
                class="bg-white rounded-xl sm:rounded-2xl border border-gray-100 p-4 sm:p-6 text-center card-hover fade-in">
                <div
                    class="w-10 h-10 sm:w-14 sm:h-14 bg-black rounded-lg sm:rounded-xl flex items-center justify-center mx-auto mb-2 sm:mb-4">
                    <svg class="w-5 h-5 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900 text-sm sm:text-base mb-0.5 sm:mb-1">Pinjam</h3>
                <p class="text-[10px] sm:text-sm text-gray-500 hidden sm:block">Ajukan peminjaman baru</p>
            </a>

            <!-- Action 2 -->
            <a href="{{ route('riwayat.index') }}"
                class="bg-white rounded-xl sm:rounded-2xl border border-gray-100 p-4 sm:p-6 text-center card-hover fade-in">
                <div
                    class="w-10 h-10 sm:w-14 sm:h-14 bg-gray-100 rounded-lg sm:rounded-xl flex items-center justify-center mx-auto mb-2 sm:mb-4">
                    <svg class="w-5 h-5 sm:w-7 sm:h-7 text-gray-600" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900 text-sm sm:text-base mb-0.5 sm:mb-1">Riwayat</h3>
                <p class="text-[10px] sm:text-sm text-gray-500 hidden sm:block">Lihat riwayat peminjaman</p>
            </a>

            <!-- Action 3 -->
            <a href="{{ route('katalog.index') }}"
                class="bg-white rounded-xl sm:rounded-2xl border border-gray-100 p-4 sm:p-6 text-center card-hover fade-in">
                <div
                    class="w-10 h-10 sm:w-14 sm:h-14 bg-gray-100 rounded-lg sm:rounded-xl flex items-center justify-center mx-auto mb-2 sm:mb-4">
                    <svg class="w-5 h-5 sm:w-7 sm:h-7 text-gray-600" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 7v10c0 2 3.5 4 8 4s8-2 8-4V7M4 7c0 2 3.5 4 8 4s8-2 8-4M4 7c0-2 3.5-4 8-4s8 2 8 4" />
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900 text-sm sm:text-base mb-0.5 sm:mb-1">Katalog</h3>
                <p class="text-[10px] sm:text-sm text-gray-500 hidden sm:block">Lihat daftar barang</p>
            </a>

            <!-- Action 4 -->
            <a href="{{ route('profil.index') }}"
                class="bg-white rounded-xl sm:rounded-2xl border border-gray-100 p-4 sm:p-6 text-center card-hover fade-in">
                <div
                    class="w-10 h-10 sm:w-14 sm:h-14 bg-gray-100 rounded-lg sm:rounded-xl flex items-center justify-center mx-auto mb-2 sm:mb-4">
                    <svg class="w-5 h-5 sm:w-7 sm:h-7 text-gray-600" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900 text-sm sm:text-base mb-0.5 sm:mb-1">Profil</h3>
                <p class="text-[10px] sm:text-sm text-gray-500 hidden sm:block">Pengaturan akun</p>
            </a>
        </div>

    </main>

    <!-- Footer -->
    <footer class="border-t border-gray-100 py-4 sm:py-6 mt-4 sm:mt-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-xs sm:text-sm text-gray-500">
                &copy; {{ date('Y') }} SIAPRAS - SMKN 1 Boyolangu
            </p>
        </div>
    </footer>

</body>

</html>