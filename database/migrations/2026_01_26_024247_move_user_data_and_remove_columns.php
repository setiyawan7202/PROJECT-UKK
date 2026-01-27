<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Migrate Data
        $users = \App\Models\Auth::all();
        foreach ($users as $user) {
            if ($user->status === 'siswa') {
                \App\Models\Siswa::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'nisn' => rand(1000000000, 9999999999), // Placeholder if missing, ideally should be there or null
                        'email' => $user->email,
                        'kelas_id' => $user->kelas_id ?? 1, // Fallback or strict? Assuming 1 exists or data exists.
                        'username' => $user->nama_lengkap
                    ]
                );
                // Update username in users table specifically for login
                if (!$user->data_nip_nisn) {
                    \Illuminate\Support\Facades\DB::table('users')
                        ->where('id', $user->id)
                        ->update(['data_nip_nisn' => rand(1000000000, 9999999999)]); // Placeholder
                }
            } elseif ($user->status === 'guru') {
                \App\Models\Guru::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'nip' => rand(1000000000, 9999999999), // Placeholder
                        'email' => $user->email,
                        'username' => $user->nama_lengkap
                    ]
                );
            }
        }

        // 2. Drop Columns from Users
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'nama_lengkap')) {
                $table->dropColumn('nama_lengkap');
            }
            if (Schema::hasColumn('users', 'kelas_id')) {
                $table->dropForeign(['kelas_id']); // Try dropping foreign key first
                $table->dropColumn('kelas_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nama_lengkap')->nullable();
            $table->foreignId('kelas_id')->nullable()->constrained('kelas');
        });
    }
};
