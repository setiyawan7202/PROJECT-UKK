<?php

namespace Database\Seeders;

use App\Models\Auth;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperadminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create superadmin user if not exists
        Auth::firstOrCreate(
            ['email' => 'superadmin@siapras.local'],
            [
                'username' => 'Super Admin',
                'email' => 'superadmin@siapras.local',
                'password' => Hash::make('superadmin123'),
                'role' => 'superadmin',
                'status' => null,
                'permissions' => null,
            ]
        );
    }
}
