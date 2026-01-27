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
            'email' => 'admin@email.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create Petugas
        Auth::create([
            'email' => 'petugas@siapras.id',
            'password' => Hash::make('petugas123'),
            'role' => 'petugas',
        ]);


    }
}
