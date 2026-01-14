@extends('layouts.admin')

@section('title', 'Tambah User')

@section('content')
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('admin.users.index') }}" class="hidden lg:inline-flex items-center gap-2 text-gray-500 hover:text-gray-900 text-sm mb-4 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
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
        <form method="POST" action="{{ route('admin.users.store') }}" class="bg-white rounded-xl lg:rounded-2xl border border-gray-100 p-6">
            @csrf

            <!-- Email -->
            <div class="mb-5">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-1 focus:ring-black transition"
                    placeholder="Masukkan email">
            </div>

            <!-- Password -->
            <div class="mb-5">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password <span class="text-red-500">*</span></label>
                <div class="relative">
                    <input type="password" id="password" name="password" required
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-1 focus:ring-black transition"
                        placeholder="Minimal 8 karakter" minlength="8">
                    <button type="button" onclick="togglePasswordVisibility('password')" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500">
                        <svg id="eye-icon-password" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg id="eye-off-icon-password" class="hidden w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Nama Lengkap -->
            <div class="mb-5">
                <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-1 focus:ring-black transition"
                    placeholder="Nama lengkap">
            </div>

            <!-- Role -->
            <div class="mb-5">
                <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Role <span class="text-red-500">*</span></label>
                <select id="role" name="role" required onchange="toggleIdentityFields()"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-1 focus:ring-black transition bg-white">
                    <option value="">Pilih Role</option>
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="petugas" {{ old('role') === 'petugas' ? 'selected' : '' }}>Petugas</option>
                    <option value="pengguna" {{ old('role') === 'pengguna' ? 'selected' : '' }}>Pengguna (Guru/Siswa)</option>
                </select>
            </div>

            <!-- Identity Section (shown when role = pengguna) -->
            <div id="identity-section" class="hidden space-y-5 p-4 bg-gray-50 rounded-xl mb-5">
                <h3 class="font-semibold text-gray-900">Data Identitas</h3>

                <!-- Identity Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Identitas <span class="text-red-500">*</span></label>
                    <div class="flex gap-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="identity_type" value="guru" onchange="toggleIdentityType()" 
                                {{ old('identity_type') === 'guru' ? 'checked' : '' }}
                                class="w-4 h-4 text-black focus:ring-black">
                            <span class="text-sm">Guru</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="identity_type" value="siswa" onchange="toggleIdentityType()"
                                {{ old('identity_type') === 'siswa' ? 'checked' : '' }}
                                class="w-4 h-4 text-black focus:ring-black">
                            <span class="text-sm">Siswa</span>
                        </label>
                    </div>
                </div>

                <!-- Guru Fields -->
                <div id="guru-fields" class="hidden">
                    <label for="nip" class="block text-sm font-medium text-gray-700 mb-2">NIP <span class="text-red-500">*</span></label>
                    <input type="text" id="nip" name="nip" value="{{ old('nip') }}"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-1 focus:ring-black transition"
                        placeholder="Nomor Induk Pegawai (18 digit)" minlength="18" maxlength="18" pattern="\d*">
                </div>

                <!-- Siswa Fields -->
                <div id="siswa-fields" class="hidden space-y-5">
                    <div>
                        <label for="nisn" class="block text-sm font-medium text-gray-700 mb-2">NISN <span class="text-red-500">*</span></label>
                        <input type="text" id="nisn" name="nisn" value="{{ old('nisn') }}"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-1 focus:ring-black transition"
                            placeholder="Nomor Induk Siswa Nasional (10 digit)" minlength="10" maxlength="10" pattern="\d*">
                    </div>
                    <div>
                        <label for="kelas_id" class="block text-sm font-medium text-gray-700 mb-2">Kelas <span class="text-red-500">*</span></label>
                        <select id="kelas_id" name="kelas_id"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-1 focus:ring-black transition bg-white">
                            <option value="">Pilih Kelas</option>
                            @foreach($kelas as $k)
                                <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>
                                    {{ $k->nama_kelas }} ({{ $k->jurusan }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex flex-col sm:flex-row gap-3">
                <button type="submit" class="flex-1 py-3 px-6 bg-black text-white rounded-xl font-semibold hover:bg-gray-800 transition">
                    Simpan User
                </button>
                <a href="{{ route('admin.users.index') }}" class="py-3 px-6 border border-gray-200 text-gray-600 rounded-xl font-medium text-center hover:bg-gray-50 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        function toggleIdentityFields() {
            const role = document.getElementById('role').value;
            const section = document.getElementById('identity-section');
            
            if (role === 'pengguna') {
                section.classList.remove('hidden');
            } else {
                section.classList.add('hidden');
            }
        }

        function toggleIdentityType() {
            const type = document.querySelector('input[name="identity_type"]:checked')?.value;
            const guruFields = document.getElementById('guru-fields');
            const siswaFields = document.getElementById('siswa-fields');
            
            if (type === 'guru') {
                guruFields.classList.remove('hidden');
                siswaFields.classList.add('hidden');
            } else if (type === 'siswa') {
                guruFields.classList.add('hidden');
                siswaFields.classList.remove('hidden');
            } else {
                guruFields.classList.add('hidden');
                siswaFields.classList.add('hidden');
            }
        }

        // Initialize on page load
        toggleIdentityFields();
        toggleIdentityType();
        
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
    </script>
@endpush
