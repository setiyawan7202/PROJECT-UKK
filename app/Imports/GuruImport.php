<?php

namespace App\Imports;

use App\Models\Auth; // User model
use App\Models\Guru;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class GuruImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Validasi dasar
        if (empty($row['nip']) || empty($row['nama_lengkap'])) {
            return null;
        }

        $nip = $row['nip'];

        // Cek duplicate
        if (Guru::where('nip', $nip)->exists()) {
            return null;
        }

        // Create User (Inactive)
        $user = Auth::create([
            'username' => $row['nama_lengkap'],
            'email' => $nip . '@teacher.temp', // Placeholder
            'data_nip_nisn' => $nip,
            'password' => Hash::make(Str::random(16)),
            'role' => 'pengguna',
            'status' => 'guru',
            'is_active' => false,
        ]);

        // Create Guru
        return new Guru([
            'user_id' => $user->id,
            'nip' => $nip,
            'username' => $row['nama_lengkap'],
            'email' => $user->email,
            'no_hp' => null,
        ]);
    }
}
