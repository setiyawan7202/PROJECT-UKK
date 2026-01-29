@extends('layouts.main')

@section('title', 'Profil Saya')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Profil Saya</h1>
            <p class="text-gray-500">Kelola informasi akun dan kata sandi Anda.</p>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-black p-8 text-center text-white">
                <div
                    class="w-24 h-24 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl font-bold backdrop-blur-sm border-2 border-white/30">
                    {{ substr($user->nama_lengkap, 0, 1) }}
                </div>
                <h2 class="text-xl font-bold">{{ $user->nama_lengkap }}</h2>
                <p class="text-gray-300">{{ $user->email }}</p>
                <div
                    class="mt-4 inline-flex items-center gap-2 bg-white/10 px-4 py-1.5 rounded-full text-xs font-medium border border-white/10">
                    <span class="capitalize">{{ $user->role }}</span>
                    @if ($user->kelas)
                        <span>•</span>
                        <span>{{ $user->kelas->nama_kelas }}</span>
                    @endif
                </div>
            </div>

            <form action="{{ route('profil.update') }}" method="POST" class="p-8 space-y-6">
                @csrf
                @method('PUT')

                <!-- Read Only Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-6 border-b border-gray-100">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-2">Nama Lengkap</label>
                        <div class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-gray-700">
                            {{ $user->nama_lengkap }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-2">Email</label>
                        <div class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-gray-700">
                            {{ $user->email }}
                        </div>
                    </div>
                </div>

                <div class="pt-2">
                    <h3 class="text-lg font-bold text-gray-900 mb-1">Ganti Kata Sandi</h3>
                    <p class="text-sm text-gray-500 mb-6">Amankan akun Anda dengan kata sandi yang kuat.</p>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kata Sandi Baru</label>
                            <input type="password" name="password" id="password"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-1 focus:ring-black transition"
                                placeholder="Minimal 8 karakter" minlength="8">
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Kata Sandi</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-1 focus:ring-black transition"
                                placeholder="Ulangi kata sandi baru" minlength="8">
                        </div>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit"
                        class="w-full bg-black text-white font-medium py-3 rounded-lg hover:bg-gray-800 transition shadow-lg shadow-gray-200">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
    </div>

    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            if (input.type === "password") {
                input.type = "text";
            } else {
                input.type = "password";
            }
        }
    </script>
    </div>
@endsection