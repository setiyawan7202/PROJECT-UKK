<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Aktivasi Akun - SIAPRAS</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            font-family: 'Inter', sans-serif;
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

    <div class="w-full max-w-lg">
        <!-- Logo & Title -->
        <div class="text-center mb-8">
            <div class="flex justify-center mb-4">
                <img src="{{ Vite::asset('resources/img/logo.png') }}" alt="Logo SIAPRAS"
                    class="w-20 h-20 object-contain">
            </div>
            <h1 class="text-3xl font-bold text-gray-900">Aktivasi Akun</h1>
            <p class="text-gray-500 mt-1">Aktifkan akun siswa atau guru Anda</p>
        </div>

        <!-- Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8">
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <ul class="text-sm text-red-600 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-sm text-red-600">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Step 1: Check Data -->
            <form id="checkForm" method="POST" action="{{ route('activation.check') }}"
                class="{{ session('user_data') ? 'hidden' : '' }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Peran</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label
                            class="flex items-center justify-center gap-2 p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition peer-checked:border-black peer-checked:ring-1 peer-checked:ring-black">
                            <input type="radio" name="status" value="siswa" checked class="w-4 h-4">
                            <span class="font-medium">Siswa</span>
                        </label>
                        <label
                            class="flex items-center justify-center gap-2 p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition">
                            <input type="radio" name="status" value="guru" class="w-4 h-4">
                            <span class="font-medium">Guru</span>
                        </label>
                    </div>
                </div>

                <div class="mb-6">
                    <label for="identifier" class="block text-sm font-medium text-gray-700 mb-2">NISN / NIP</label>
                    <input type="text" name="identifier" required
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-black focus:ring-1 focus:ring-black outline-none transition"
                        placeholder="Masukkan NISN atau NIP">
                </div>

                <button type="submit"
                    class="w-full py-3 bg-black text-white rounded-xl font-semibold hover:bg-gray-800 transition">
                    Cek Data
                </button>
            </form>

            <!-- Step 2: Complete Data -->
            @if (session('user_data'))
                <form method="POST" action="{{ route('activation.submit') }}" class="fade-in">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ session('user_data')['id'] }}">

                    <div class="bg-gray-50 p-4 rounded-xl mb-6 border border-gray-100">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-semibold">Nama Lengkap</p>
                                <p class="font-medium text-gray-900">{{ session('user_data')['name'] }}</p>
                            </div>
                            <span
                                class="px-2 py-1 bg-black text-white text-xs rounded-md uppercase">{{ session('user_data')['status'] }}</span>
                        </div>
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-semibold">ID (NISN/NIP)</p>
                                <p class="font-medium text-gray-900">{{ session('user_data')['identifier'] }}</p>
                            </div>
                            @if(isset(session('user_data')['kelas']))
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-semibold text-right">Kelas</p>
                                    <p class="font-medium text-gray-900 text-right">{{ session('user_data')['kelas'] }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Aktif</label>
                        <input type="email" name="email" required value="{{ old('email') }}"
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-black focus:ring-1 focus:ring-black outline-none transition"
                            placeholder="Email untuk menerima password">
                        <p class="text-xs text-gray-500 mt-1">Password akan dikirim ke email ini.</p>
                    </div>

                    <div class="mb-6">
                        <label for="no_hp" class="block text-sm font-medium text-gray-700 mb-2">No. HP (Opsional)</label>
                        <input type="text" name="no_hp" value="{{ old('no_hp') }}"
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-black focus:ring-1 focus:ring-black outline-none transition"
                            placeholder="Nomor WhatsApp aktif">
                    </div>

                    <div class="flex gap-3">
                        <a href="{{ route('activation.form') }}"
                            class="w-1/3 py-3 text-center bg-gray-100 text-gray-700 rounded-xl font-semibold hover:bg-gray-200 transition">
                            Batal
                        </a>
                        <button type="submit"
                            class="w-2/3 py-3 bg-black text-white rounded-xl font-semibold hover:bg-gray-800 transition">
                            Aktifkan Akun
                        </button>
                    </div>
                </form>
            @endif

        </div>

        <!-- Back Link -->
        <div class="text-center mt-6">
            <p class="text-gray-500 text-sm mb-4">
                Sudah punya akun aktif?
                <a href="{{ route('login') }}" class="text-black font-medium hover:underline">Masuk di sini</a>
            </p>
            <a href="/" class="text-gray-500 hover:text-gray-700 text-sm transition">
                ← Kembali ke Beranda
            </a>
        </div>
    </div>

</body>

</html>