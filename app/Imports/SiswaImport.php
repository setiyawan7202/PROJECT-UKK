<?php

namespace App\Imports;

use App\Models\Auth; // User model
use App\Models\Siswa;
use App\Models\Kelas;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SiswaImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Validasi dasar row
        if (empty($row['nisn']) || empty($row['nama_lengkap'])) {
            return null;
        }

        $nisn = $row['nisn'];

        // Cek jika siswa sudah ada (berdasarkan NISN)
        if (Siswa::where('nisn', $nisn)->exists()) {
            return null; // Skip duplicate
        }

        // Cari kelas berdasarkan nama (e.g. "X RPL 1")
        $kelasId = null;
        if (!empty($row['kelas'])) {
            $kelas = Kelas::where('nama_kelas', 'like', '%' . $row['kelas'] . '%')->first();
            $kelasId = $kelas ? $kelas->id : null;
        }

        // Create User (Inactive)
        $user = Auth::create([
            'username' => $row['nama_lengkap'],
            'email' => $nisn . '@student.temp', // Placeholder email, user will update it
            'data_nip_nisn' => $nisn,
            'password' => Hash::make(Str::random(16)), // Random password initially
            'role' => 'pengguna',
            'status' => 'siswa',
            'is_active' => false, // Penting!
        ]);

        // Create Siswa
        return new Siswa([
            'user_id' => $user->id,
            'nisn' => $nisn,
            'username' => $row['nama_lengkap'],
            'email' => $user->email,
            'kelas_id' => $kelasId,
            'no_hp' => null,
        ]);
    }
}
