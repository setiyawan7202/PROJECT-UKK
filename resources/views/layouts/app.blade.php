<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') - SIAPRAS</title>

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

<body class="min-h-screen bg-gray-50 overflow-hidden h-screen w-screen">

    <!-- Navbar -->
    <nav class="bg-white border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-14 sm:h-16">
                <!-- Logo -->
                <a href="{{ route('main.index') }}" class="flex items-center gap-2 sm:gap-3">
                    <img src="{{ Vite::asset('resources/img/logo.png') }}" alt="Logo"
                        class="w-8 h-8 sm:w-10 sm:h-10 object-contain">
                    <span class="text-lg sm:text-xl font-bold">SIAPRAS</span>
                </a>

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
    <main class="h-[calc(100vh-theme('spacing.14'))] sm:h-[calc(100vh-theme('spacing.16'))] overflow-y-auto w-full">
        @yield('content')

        <!-- Footer inside main scroll area -->
        <footer class="border-t border-gray-100 py-4 sm:py-6 mt-4 sm:mt-8 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <p class="text-xs sm:text-sm text-gray-500">
                    &copy; {{ date('Y') }} SIAPRAS - SMKN 1 Boyolangu
                </p>
            </div>
        </footer>
    </main>



</body>

</html>