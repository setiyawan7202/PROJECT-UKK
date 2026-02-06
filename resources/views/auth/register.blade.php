<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Daftar - SIAPRAS</title>

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

        .btn-register {
            transition: all 0.3s ease;
        }

        .btn-register:hover {
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

    <div class="w-full max-w-lg fade-in">
        <!-- Logo & Title -->
        <div class="text-center mb-8">
            <div class="flex justify-center mb-4">
                <img src="{{ Vite::asset('resources/img/logo.png') }}" alt="Logo SIAPRAS"
                    class="w-20 h-20 object-contain">
            </div>
            <h1 class="text-3xl font-bold text-gray-900">SIAPRAS</h1>
            <p class="text-gray-500 mt-1">Daftar Akun Baru</p>
        </div>

        <!-- Registration Form -->
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

            <form method="POST" action="{{ route('register.submit') }}">
                @csrf

                <!-- Nama Lengkap -->
                <div class="mb-4">
                    <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                    <input type="text" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-black focus:ring-1 focus:ring-black outline-none transition"
                        placeholder="Masukkan nama lengkap">
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-black focus:ring-1 focus:ring-black outline-none transition"
                        placeholder="Masukkan email aktif">
                </div>

                <!-- Status (Siswa/Guru) -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Daftar Sebagai</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="flex items-center justify-center gap-2 p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition">
                            <input type="radio" name="status" value="siswa" {{ old('status') == 'siswa' ? 'checked' : '' }} 
                                onchange="toggleFields()" class="w-4 h-4">
                            <span class="font-medium">Siswa</span>
                        </label>
                        <label class="flex items-center justify-center gap-2 p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition">
                            <input type="radio" name="status" value="guru" {{ old('status') == 'guru' ? 'checked' : '' }}
                                onchange="toggleFields()" class="w-4 h-4">
                            <span class="font-medium">Guru</span>
                        </label>
                    </div>
                </div>

                <!-- Siswa Fields -->
                <div id="siswa-fields" class="hidden">
                    <div class="mb-4">
                        <label for="nisn" class="block text-sm font-medium text-gray-700 mb-2">NISN</label>
                        <input type="text" id="nisn" name="nisn" value="{{ old('nisn') }}"
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-black focus:ring-1 focus:ring-black outline-none transition"
                            placeholder="Masukkan NISN">
                    </div>

                    <div class="mb-4">
                        <label for="kelas_id" class="block text-sm font-medium text-gray-700 mb-2">Kelas</label>
                        <select id="kelas_id" name="kelas_id"
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-black focus:ring-1 focus:ring-black outline-none transition">
                            <option value="">Pilih Kelas</option>
                            @foreach ($kelasList as $kelas)
                                <option value="{{ $kelas->id }}" {{ old('kelas_id') == $kelas->id ? 'selected' : '' }}>
                                    {{ $kelas->tingkat }} - {{ $kelas->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Guru Fields -->
                <div id="guru-fields" class="hidden">
                    <div class="mb-4">
                        <label for="nip" class="block text-sm font-medium text-gray-700 mb-2">NIP</label>
                        <input type="text" id="nip" name="nip" value="{{ old('nip') }}"
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-black focus:ring-1 focus:ring-black outline-none transition"
                            placeholder="Masukkan NIP">
                    </div>
                </div>

                <!-- No HP (Optional) -->
                <div class="mb-4">
                    <label for="no_hp" class="block text-sm font-medium text-gray-700 mb-2">No. HP (Opsional)</label>
                    <input type="text" id="no_hp" name="no_hp" value="{{ old('no_hp') }}"
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-black focus:ring-1 focus:ring-black outline-none transition"
                        placeholder="Masukkan nomor HP">
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input type="password" id="password" name="password" required minlength="8"
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-black focus:ring-1 focus:ring-black outline-none transition"
                        placeholder="Minimal 8 karakter">
                </div>

                <!-- Confirm Password -->
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required minlength="8"
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-black focus:ring-1 focus:ring-black outline-none transition"
                        placeholder="Ulangi password">
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="w-full py-4 bg-black text-white rounded-xl font-semibold text-lg btn-register">
                    Daftar
                </button>
            </form>
        </div>

        <!-- Login Link -->
        <div class="text-center mt-6">
            <p class="text-gray-500 text-sm">
                Sudah punya akun? 
                <a href="{{ route('login') }}" class="text-black font-medium hover:underline">Masuk di sini</a>
            </p>
        </div>

        <!-- Back Link -->
        <div class="text-center mt-4">
            <a href="/" class="text-gray-500 hover:text-gray-700 text-sm transition">
                ← Kembali ke Beranda
            </a>
        </div>
    </div>

    <script>
        function toggleFields() {
            const status = document.querySelector('input[name="status"]:checked');
            const siswaFields = document.getElementById('siswa-fields');
            const guruFields = document.getElementById('guru-fields');

            siswaFields.classList.add('hidden');
            guruFields.classList.add('hidden');

            if (status) {
                if (status.value === 'siswa') {
                    siswaFields.classList.remove('hidden');
                } else if (status.value === 'guru') {
                    guruFields.classList.remove('hidden');
                }
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', toggleFields);
    </script>

</body>

</html>
