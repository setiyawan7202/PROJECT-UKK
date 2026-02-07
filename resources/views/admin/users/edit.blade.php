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

            <!-- Status Aktivasi -->
            <div class="mb-5 p-4 rounded-xl border {{ $user->is_active ? 'bg-green-50 border-green-200' : 'bg-yellow-50 border-yellow-200' }}">
                <div class="flex items-center gap-3">
                    @if($user->is_active)
                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                        <div>
                            <p class="text-sm font-medium text-green-800">Akun Aktif</p>
                            <p class="text-xs text-green-600">User dapat login dengan email di bawah</p>
                        </div>
                    @else
                        <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                        <div>
                            <p class="text-sm font-medium text-yellow-800">Akun Belum Aktif</p>
                            <p class="text-xs text-yellow-600">Isi email untuk mengaktifkan akun, atau biarkan user aktivasi sendiri</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Email -->
            <div class="mb-5">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email 
                    <span class="text-gray-400 font-normal">(Kosongkan untuk menonaktifkan akun)</span>
                </label>
                @php
                    $displayEmail = $user->email;
                    if ($displayEmail && str_ends_with($displayEmail, '@temp.local')) {
                        $displayEmail = '';
                    }
                @endphp
                <input type="email" id="email" name="email" value="{{ old('email', $displayEmail) }}"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-1 focus:ring-black transition"
                    placeholder="Masukkan email untuk mengaktifkan akun">
                <p class="text-xs text-gray-500 mt-1">Jika email dikosongkan, akun akan dinonaktifkan dan user harus aktivasi ulang.</p>
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
                @php
                    $namaLengkap = $user->siswa?->username ?? $user->guru?->username ?? $user->username;
                @endphp
                <input type="text" id="nama_lengkap" name="nama_lengkap" required
                    value="{{ old('nama_lengkap', $namaLengkap) }}"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-1 focus:ring-black transition"
                    placeholder="Nama lengkap">
            </div>

            <!-- Role -->
            <div class="mb-5">
                <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Role <span
                        class="text-red-500">*</span></label>
                <select id="role" name="role" required
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-1 focus:ring-black transition bg-white"
                    @if(Auth::user()->role !== 'superadmin' && in_array($user->role, ['superadmin', 'admin'])) disabled @endif>
                    @if(Auth::user()->role === 'superadmin')
                        <option value="superadmin" {{ old('role', $user->role) === 'superadmin' ? 'selected' : '' }}>Super Admin</option>
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                    @endif
                    <option value="petugas" {{ old('role', $user->role) === 'petugas' ? 'selected' : '' }}>Petugas</option>
                    <option value="kepala_lab" {{ old('role', $user->role) === 'kepala_lab' ? 'selected' : '' }}>Kepala Lab</option>
                    <option value="pengguna" {{ old('role', $user->role) === 'pengguna' ? 'selected' : '' }}>Pengguna</option>
                </select>
                @if(Auth::user()->role !== 'superadmin' && in_array($user->role, ['superadmin', 'admin']))
                    <input type="hidden" name="role" value="{{ $user->role }}">
                    <p class="mt-1 text-xs text-amber-600">Hanya Super Admin yang dapat mengubah role ini.</p>
                @endif
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

            <!-- No HP (Optional) -->
            <div class="mb-5" id="no_hp-field"
                style="{{ in_array(old('status', $user->status), ['siswa', 'guru']) ? '' : 'display: none;' }}">
                <label for="no_hp" class="block text-sm font-medium text-gray-700 mb-2">No. HP <span
                        class="text-gray-400 font-normal">(Opsional)</span></label>
                <div class="relative">
                    <input type="text" id="no_hp" name="no_hp"
                        value="{{ old('no_hp', $user->siswa ? $user->siswa->no_hp : ($user->guru ? $user->guru->no_hp : '')) }}"
                        class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-1 focus:ring-black transition"
                        placeholder="Contoh: 08123456789">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-medium">
                        +62
                    </div>
                </div>
                <p class="mt-1 text-xs text-gray-500">Nomor WhatsApp aktif direkomendasikan.</p>
            </div>

            <!-- NISN (only shown when status = siswa) -->
            <div class="mb-5" id="nisn-field"
                style="{{ old('status', $user->status) === 'siswa' ? '' : 'display: none;' }}">
                <label for="nisn" class="block text-sm font-medium text-gray-700 mb-2">NISN <span
                        class="text-red-500">*</span></label>
                <input type="number" id="nisn" name="nisn" value="{{ old('nisn', $user->siswa?->nisn) }}"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-1 focus:ring-black transition"
                    placeholder="Masukkan NISN">
            </div>

            <!-- NIP (only shown when status = guru) -->
            <div class="mb-5" id="nip-field" style="{{ old('status', $user->status) === 'guru' ? '' : 'display: none;' }}">
                <label for="nip" class="block text-sm font-medium text-gray-700 mb-2">NIP <span
                        class="text-red-500">*</span></label>
                <input type="number" id="nip" name="nip" value="{{ old('nip', $user->guru?->nip) }}"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-1 focus:ring-black transition"
                    placeholder="Masukkan NIP">
            </div>

            <!-- Kelas (only shown when status = siswa) -->
            <div class="mb-5" id="kelas-field"
                style="{{ old('status', $user->status) === 'siswa' ? '' : 'display: none;' }}">
                <label for="kelas_id" class="block text-sm font-medium text-gray-700 mb-2">Kelas</label>
                <select id="kelas_id" name="kelas_id" class="searchable-select w-full">
                    <option value="">Pilih Kelas</option>
                    @foreach($kelasList as $kelas)
                        <option value="{{ $kelas->id }}" {{ old('kelas_id', $user->siswa?->kelas_id) == $kelas->id ? 'selected' : '' }}>
                            {{ $kelas->nama_kelas }} ({{ $kelas->jurusan }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Permissions (Only for Petugas) -->
            <div class="mb-5" id="permissions-field" style="{{ old('role', $user->role) === 'petugas' ? '' : 'display: none;' }}">
                <label class="block text-sm font-medium text-gray-700 mb-3">Hak Akses Petugas</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 bg-gray-50 p-4 rounded-xl border border-gray-100">
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" name="permissions[]" value="manage_barang" 
                            class="w-5 h-5 text-black rounded border-gray-300 focus:ring-black transition"
                            {{ in_array('manage_barang', $user->permissions ?? []) ? 'checked' : '' }}>
                        <span class="text-sm text-gray-700">Kelola Barang</span>
                    </label>
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" name="permissions[]" value="manage_kategori" 
                            class="w-5 h-5 text-black rounded border-gray-300 focus:ring-black transition"
                            {{ in_array('manage_kategori', $user->permissions ?? []) ? 'checked' : '' }}>
                        <span class="text-sm text-gray-700">Kelola Kategori</span>
                    </label>
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" name="permissions[]" value="manage_ruangan" 
                            class="w-5 h-5 text-black rounded border-gray-300 focus:ring-black transition"
                            {{ in_array('manage_ruangan', $user->permissions ?? []) ? 'checked' : '' }}>
                        <span class="text-sm text-gray-700">Kelola Ruangan</span>
                    </label>
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" name="permissions[]" value="manage_users" 
                            class="w-5 h-5 text-black rounded border-gray-300 focus:ring-black transition"
                            {{ in_array('manage_users', $user->permissions ?? []) ? 'checked' : '' }}>
                        <span class="text-sm text-gray-700">Kelola User (Siswa/Guru)</span>
                    </label>
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" name="permissions[]" value="manage_kelas" 
                            class="w-5 h-5 text-black rounded border-gray-300 focus:ring-black transition"
                            {{ in_array('manage_kelas', $user->permissions ?? []) ? 'checked' : '' }}>
                        <span class="text-sm text-gray-700">Kelola Kelas</span>
                    </label>
                </div>
                <p class="text-xs text-gray-500 mt-2">Centang fitur yang ingin diberikan akses edit/hapus kepada petugas.</p>
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
                document.getElementById('nisn-field').style.display = 'block';
                document.getElementById('nip-field').style.display = 'none';
                document.getElementById('no_hp-field').style.display = 'block';
            } else if (status === 'guru') {
                kelasField.style.display = 'none';
                document.getElementById('kelas_id').value = '';
                document.getElementById('nisn-field').style.display = 'none';
                document.getElementById('nip-field').style.display = 'block';
                document.getElementById('no_hp-field').style.display = 'block';
            } else {
                kelasField.style.display = 'none';
                document.getElementById('kelas_id').value = '';
                document.getElementById('nisn-field').style.display = 'none';
                document.getElementById('nip-field').style.display = 'none';
                document.getElementById('no_hp-field').style.display = 'none';
            }
        }

        // Toggle Permissions Field
        document.getElementById('role').addEventListener('change', function() {
            const role = this.value;
            const permField = document.getElementById('permissions-field');
            if(role === 'petugas') {
                permField.style.display = 'block';
            } else {
                permField.style.display = 'none';
            }
        });
    </script>
@endpush