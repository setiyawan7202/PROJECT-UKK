@extends('layouts.admin')

@section('title', 'Edit User')

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
            <h1 class="text-xl lg:text-2xl font-bold text-gray-900">Edit User</h1>
            <p class="text-sm text-gray-500">Perbarui data user</p>
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
        <form method="POST" action="{{ route('admin.users.update', $user->id) }}"
            class="bg-white rounded-xl lg:rounded-2xl border border-gray-100 p-6">
            @csrf
            @method('PUT')

            <!-- Email -->
            <div class="mb-5">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email <span
                        class="text-red-500">*</span></label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-1 focus:ring-black transition"
                    placeholder="Masukkan email">
            </div>

            <!-- Password -->
            <div class="mb-5">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                <div class="relative">
                    <input type="password" id="password" name="password"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-1 focus:ring-black transition"
                        placeholder="Kosongkan jika tidak ingin mengubah" minlength="8">
                </div>
            </div>

            <!-- Nama Lengkap -->
            <div class="mb-5">
                <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap <span
                        class="text-red-500">*</span></label>
                <input type="text" id="nama_lengkap" name="nama_lengkap" required
                    value="{{ old('nama_lengkap', $user->nama_lengkap) }}"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-1 focus:ring-black transition"
                    placeholder="Nama lengkap">
            </div>

            <!-- Role -->
            <div class="mb-5">
                <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Role <span
                        class="text-red-500">*</span></label>
                <select id="role" name="role" required
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-1 focus:ring-black transition bg-white">
                    <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="petugas" {{ old('role', $user->role) === 'petugas' ? 'selected' : '' }}>Petugas</option>
                    <option value="pengguna" {{ old('role', $user->role) === 'pengguna' ? 'selected' : '' }}>Pengguna</option>
                </select>
            </div>

            <!-- Status -->
            <div class="mb-5">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select id="status" name="status"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-1 focus:ring-black transition bg-white"
                    onchange="toggleKelasField()">
                    <option value="">Tidak ada</option>
                    <option value="siswa" {{ old('status', $user->status) === 'siswa' ? 'selected' : '' }}>Siswa</option>
                    <option value="guru" {{ old('status', $user->status) === 'guru' ? 'selected' : '' }}>Guru</option>
                </select>
            </div>

            <!-- Kelas (only shown when status = siswa) -->
            <div class="mb-5" id="kelas-field"
                style="{{ old('status', $user->status) === 'siswa' ? '' : 'display: none;' }}">
                <label for="kelas_id" class="block text-sm font-medium text-gray-700 mb-2">Kelas</label>
                <select id="kelas_id" name="kelas_id"
                    class="searchable-select w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-1 focus:ring-black transition bg-white">
                    <option value="">Pilih Kelas</option>
                    @foreach($kelasList as $kelas)
                        <option value="{{ $kelas->id }}" {{ old('kelas_id', $user->kelas_id) == $kelas->id ? 'selected' : '' }}>
                            {{ $kelas->nama_kelas }} ({{ $kelas->jurusan }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Submit -->
            <div class="flex flex-col sm:flex-row gap-3">
                <button type="submit"
                    class="flex-1 py-3 px-6 bg-black text-white rounded-xl font-semibold hover:bg-gray-800 transition">
                    Simpan Perubahan
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
            } else {
                kelasField.style.display = 'none';
                document.getElementById('kelas_id').value = '';
            }
        }
    </script>
@endpush