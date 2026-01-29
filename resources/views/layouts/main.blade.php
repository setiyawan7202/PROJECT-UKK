<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'SIAPRAS')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Force scrollable behavior */
        html {
            overflow-y: scroll !important;
            overflow-x: hidden !important;
            height: 100% !important;
            scroll-behavior: smooth;
        }

        body {
            overflow-y: visible !important;
            overflow-x: hidden !important;
            height: auto !important;
            min-height: 100vh !important;
            max-height: none !important;
            position: relative !important;
        }

        /* Ensure no elements block scroll */
        * {
            font-family: 'Inter', sans-serif;
            max-height: none !important;
        }

        /* Allow main content to scroll */
        main {
            overflow: visible !important;
            height: auto !important;
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
    @stack('styles')
</head>

<body class="min-h-screen bg-gray-50">

    <!-- Navbar -->
    <nav class="bg-white border-b border-gray-100 fixed top-0 left-0 right-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-14 sm:h-16">
                <!-- Logo -->
                <div class="flex items-center gap-2 sm:gap-3">
                    <a href="{{ route('main.index') }}" class="flex items-center gap-2 sm:gap-3">
                        <img src="{{ Vite::asset('resources/img/logo.png') }}" alt="Logo"
                            class="w-8 h-8 sm:w-10 sm:h-10 object-contain">
                        <span class="text-lg sm:text-xl font-bold">SIAPRAS</span>
                    </a>
                </div>

                <!-- User Menu -->
                <div class="flex items-center gap-2 sm:gap-4">
                    <!-- Cart Icon -->
                    <a href="{{ route('cart.index') }}" class="relative p-2 text-gray-600 hover:text-black transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        @if(session()->has('cart') && count(session('cart')) > 0)
                            <span
                                class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/4 -translate-y-1/4 bg-red-600 rounded-full">
                                {{ count(session('cart')) }}
                            </span>
                        @endif
                    </a>

                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-medium text-gray-900 truncate max-w-[150px]">
                            {{ Auth::user()->nama_lengkap }}
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
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-8 pt-20 sm:pt-24">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Berhasil!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="border-t border-gray-100 py-4 sm:py-6 mt-4 sm:mt-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-xs sm:text-sm text-gray-500">
                &copy; {{ date('Y') }} SIAPRAS - SMKN 1 Boyolangu
            </p>
        </div>
    </footer>
    @stack('scripts')
</body>

</html>