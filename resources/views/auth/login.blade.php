<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login - SIAPRAS</title>

    <!-- Fonts -->
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

        .input-focus:focus {
            outline: none;
            border-color: #000;
            box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.1);
        }

        .btn-login {
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        .gradient-bg {
            background: linear-gradient(-45deg, #fafafa, #ffffff, #f5f5f5, #ffffff);
            background-size: 400% 400%;
            animation: gradientMove 15s ease infinite;
        }

        @keyframes gradientMove {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }
    </style>
</head>

<body class="min-h-screen gradient-bg flex items-center justify-center px-4 py-8">

    <div class="w-full max-w-md fade-in">
        <!-- Logo & Title -->
        <div class="text-center mb-8">
            <div class="flex justify-center mb-4">
                <img src="{{ Vite::asset('resources/img/logo.png') }}" alt="Logo SIAPRAS"
                    class="w-20 h-20 object-contain">
            </div>
            <h1 class="text-3xl font-bold text-gray-900">SIAPRAS</h1>
            <p class="text-gray-500 mt-1">Masuk ke Sistem</p>
        </div>

        <!-- Login Form -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8">
            <form method="POST" action="{{ route('login.submit') }}">
                @csrf

                <!-- Email -->
                <div class="mb-5">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 input-focus transition"
                        placeholder="Masukkan email" required>
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- NISN / NIP -->
                <div class="mb-5">
                    <label for="identifier" class="block text-sm font-medium text-gray-700 mb-2">
                        NISN / NIP
                    </label>
                    <input type="text" id="identifier" name="identifier" value="{{ old('identifier') }}"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 input-focus transition"
                        placeholder="Masukkan NISN atau NIP" minlength="8" maxlength="18" required>
                    @error('identifier')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Password
                    </label>
                    <input type="password" id="password" name="password"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 input-focus transition"
                        placeholder="Masukkan password" minlength="8" required>
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="w-full py-4 bg-black text-white rounded-xl font-semibold text-lg btn-login">
                    Masuk
                </button>
            </form>
        </div>

        <!-- Back Link -->
        <div class="text-center mt-6">
            <a href="/" class="text-gray-500 hover:text-gray-700 text-sm transition">
                ← Kembali ke Beranda
            </a>
        </div>
    </div>

</body>

</html>