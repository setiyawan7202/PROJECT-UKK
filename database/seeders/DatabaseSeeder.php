<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Auth;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Kelas
        $kelas1 = Kelas::create([
            'nama_kelas' => 'XII RPL 1',
            'jurusan' => 'RPL',
            'tingkat' => 'XII',
        ]);

        $kelas2 = Kelas::create([
            'nama_kelas' => 'XII RPL 2',
            'jurusan' => 'RPL',
            'tingkat' => 'XII',
        ]);

        $kelas3 = Kelas::create([
            'nama_kelas' => 'XI TKJ 1',
            'jurusan' => 'TKJ',
            'tingkat' => 'XI',
        ]);

        $kelas4 = Kelas::create([
            'nama_kelas' => 'X MM 1',
            'jurusan' => 'MM',
            'tingkat' => 'X',
        ]);

        // Create Admin
        Auth::create([
            'email' => 'admin@siapras.id',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // Create Petugas
        Auth::create([
            'email' => 'petugas@siapras.id',
            'password' => Hash::make('petugas123'),
            'role' => 'petugas',
        ]);

        // Create Guru (Pengguna)
        $guruUser = Auth::create([
            'email' => 'guru@siapras.id',
            'password' => Hash::make('guru123'),
            'role' => 'pengguna',
        ]);

        Guru::create([
            'user_id' => $guruUser->id,
            'nama_lengkap' => 'Budi Santoso, S.Pd',
            'email' => 'guru@siapras.id',
            'nip' => '198501012010011001',
        ]);

        // Create Siswa (Pengguna)
        $siswaUser = Auth::create([
            'email' => 'siswa@siapras.id',
            'password' => Hash::make('siswa123'),
            'role' => 'pengguna',
        ]);

        Siswa::create([
            'user_id' => $siswaUser->id,
            'kelas_id' => $kelas1->id,
            'nama_lengkap' => 'Ahmad Rizki',
            'email' => 'siswa@siapras.id',
            'nisn' => '0012345678',
        ]);
    }
}
