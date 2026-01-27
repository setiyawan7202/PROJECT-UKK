@extends('layouts.admin')

@section('title', 'Tambah User')

@section('content')
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('admin.users.index') }}"
                class="hidden lg:inline-flex items-center gap-2 text-gray-500 hover:text-gray-900 text-sm mb-4 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali ke Daftar User
            </a>
            <h1 class="text-xl lg:text-2xl font-bold text-gray-900">Tambah User Baru</h1>
            <p class="text-sm text-gray-500">Isi form berikut untuk menambahkan user baru</p>
        </div>

        <!-- Error Messages -->
        @if($errors->any())
            <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('admin.users.store') }}"
            class="bg-white rounded-xl lg:rounded-2xl border border-gray-100 p-6">
            @csrf

            <!-- Email -->
            <div class="mb-5">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email <span
                        class="text-red-500">*</span></label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-1 focus:ring-black transition"
                    placeholder="Masukkan email">
            </div>

            <!-- Password -->
            <div class="mb-5">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password <span
                        class="text-red-500">*</span></label>
                <div class="relative">
                    <input type="password" id="password" name="password" required
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-1 focus:ring-black transition pr-32"
                        placeholder="Minimal 8 karakter" minlength="8">
                    <div class="absolute right-2 top-1/2 -translate-y-1/2 flex items-center gap-1">
                        <button type="button" onclick="generatePassword()"
                            class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1.5 rounded-lg transition font-medium">
                            Generate
                        </button>
                        <button type="button" onclick="togglePasswordVisibility('password')"
                            class="p-2 hover:bg-gray-100 rounded-lg text-gray-400 hover:text-gray-600 transition">
                            <svg id="eye-icon-password" class="w-5 h-5 hidden" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg id="eye-off-icon-password" class="w-5 h-5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Nama Lengkap -->
            <div class="mb-5">
                <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap <span
                        class="text-red-500">*</span></label>
                <input type="text" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-1 focus:ring-black transition"
                    placeholder="Nama lengkap">
            </div>

            <!-- Role -->
            <div class="mb-5">
                <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Role <span
                        class="text-red-500">*</span></label>
                <select id="role" name="role" required
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-1 focus:ring-black transition bg-white">
                    <option value="">Pilih Role</option>
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="petugas" {{ old('role') === 'petugas' ? 'selected' : '' }}>Petugas</option>
                    <option value="pengguna" {{ old('role') === 'pengguna' ? 'selected' : '' }}>Pengguna</option>
                </select>
            </div>

            <!-- Status -->
            <div class="mb-5">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select id="status" name="status"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-1 focus:ring-black transition bg-white"
                    onchange="toggleKelasField()">
                    <option value="">Tidak ada</option>
                    <option value="siswa" {{ old('status') === 'siswa' ? 'selected' : '' }}>Siswa</option>
                    <option value="guru" {{ old('status') === 'guru' ? 'selected' : '' }}>Guru</option>
                </select>
            </div>

            <!-- NISN (only shown when status = siswa) -->
            <div class="mb-5" id="nisn-field" style="{{ old('status') === 'siswa' ? '' : 'display: none;' }}">
                <label for="nisn" class="block text-sm font-medium text-gray-700 mb-2">NISN <span
                        class="text-red-500">*</span></label>
                <input type="number" id="nisn" name="nisn" value="{{ old('nisn') }}"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-1 focus:ring-black transition"
                    placeholder="Masukkan NISN">
            </div>

            <!-- NIP (only shown when status = guru) -->
            <div class="mb-5" id="nip-field" style="{{ old('status') === 'guru' ? '' : 'display: none;' }}">
                <label for="nip" class="block text-sm font-medium text-gray-700 mb-2">NIP <span
                        class="text-red-500">*</span></label>
                <input type="number" id="nip" name="nip" value="{{ old('nip') }}"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-1 focus:ring-black transition"
                    placeholder="Masukkan NIP">
            </div>

            <!-- Kelas (only shown when status = siswa) -->
            <div class="mb-5" id="kelas-field" style="{{ old('status') === 'siswa' ? '' : 'display: none;' }}">
                <label for="kelas_id" class="block text-sm font-medium text-gray-700 mb-2">Kelas</label>
                <select id="kelas_id" name="kelas_id" class="searchable-select w-full">
                    <option value="">Pilih Kelas</option>
                    @foreach($kelasList as $kelas)
                        <option value="{{ $kelas->id }}" {{ old('kelas_id') == $kelas->id ? 'selected' : '' }}>
                            {{ $kelas->nama_kelas }} ({{ $kelas->jurusan }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Submit -->
            <div class="flex flex-col sm:flex-row gap-3">
                <button type="submit"
                    class="flex-1 py-3 px-6 bg-black text-white rounded-xl font-semibold hover:bg-gray-800 transition">
                    Simpan User
                </button>
                <a href="{{ route('admin.users.index') }}"
                    class="py-3 px-6 border border-gray-200 text-gray-600 rounded-xl font-medium text-center hover:bg-gray-50 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        function generatePassword() {
            const length = 12;
            const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*";
            let retVal = "";
            for (let i = 0, n = charset.length; i < length; ++i) {
                retVal += charset.charAt(Math.floor(Math.random() * n));
            }
            const passwordInput = document.getElementById('password');
            passwordInput.value = retVal;

            // Show password so user can copy it
            if (passwordInput.type === 'password') {
                togglePasswordVisibility('password');
            }
        }

        function togglePasswordVisibility(id) {
            const input = document.getElementById(id);
            const eyeIcon = document.getElementById('eye-icon-' + id);
            const eyeOffIcon = document.getElementById('eye-off-icon-' + id);

            if (input.type === 'password') {
                input.type = 'text';
                eyeIcon.classList.add('hidden');
                eyeOffIcon.classList.remove('hidden');
            } else {
                input.type = 'password';
                eyeIcon.classList.remove('hidden');
                eyeOffIcon.classList.add('hidden');
            }
        }

        function toggleKelasField() {
            const status = document.getElementById('status').value;
            const kelasField = document.getElementById('kelas-field');

            if (status === 'siswa') {
                kelasField.style.display = 'block';
                document.getElementById('nisn-field').style.display = 'block';
                document.getElementById('nip-field').style.display = 'none';
            } else if (status === 'guru') {
                kelasField.style.display = 'none';
                document.getElementById('kelas_id').value = '';
                document.getElementById('nisn-field').style.display = 'none';
                document.getElementById('nip-field').style.display = 'block';
            } else {
                kelasField.style.display = 'none';
                document.getElementById('kelas_id').value = '';
                document.getElementById('nisn-field').style.display = 'none';
                document.getElementById('nip-field').style.display = 'none';
            }
        }
        document.addEventListener('DOMContentLoaded', function () {
            generatePassword();
        });
    </script>
@endpush