<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Auth;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin
        Auth::create([
            'email' => 'admin@siapras.id',
            'password' => Hash::make('admin123'),
            'nama_lengkap' => 'Administrator',
            'role' => 'admin',
        ]);

        // Create Petugas
        Auth::create([
            'email' => 'petugas@siapras.id',
            'password' => Hash::make('petugas123'),
            'nama_lengkap' => 'Petugas Sarana',
            'role' => 'petugas',
        ]);

        // Create Pengguna
        Auth::create([
            'email' => 'pengguna@siapras.id',
            'password' => Hash::make('pengguna123'),
            'nama_lengkap' => 'User Pengguna',
            'role' => 'pengguna',
        ]);
    }
}
