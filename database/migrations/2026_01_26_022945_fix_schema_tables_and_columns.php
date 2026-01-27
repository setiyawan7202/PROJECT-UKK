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
        // Fix Table Names
        if (Schema::hasTable('siswas') && !Schema::hasTable('siswa')) {
            Schema::rename('siswas', 'siswa');
        }
        if (Schema::hasTable('gurus') && !Schema::hasTable('guru')) {
            Schema::rename('gurus', 'guru');
        }

        // Fix Users Column
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'username') && !Schema::hasColumn('users', 'data_nip_nisn')) {
                $table->renameColumn('username', 'data_nip_nisn');
            } elseif (!Schema::hasColumn('users', 'data_nip_nisn')) {
                $table->string('data_nip_nisn')->nullable()->unique()->after('email');
            }
        });

        // Add username (Nama Lengkap) to Child Tables
        if (Schema::hasTable('siswa')) {
            Schema::table('siswa', function (Blueprint $table) {
                if (!Schema::hasColumn('siswa', 'username')) {
                    $table->string('username')->nullable()->after('nisn');
                }
            });
        }

        if (Schema::hasTable('guru')) {
            Schema::table('guru', function (Blueprint $table) {
                if (!Schema::hasColumn('guru', 'username')) {
                    $table->string('username')->nullable()->after('nip');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Optional: Define rollback if needed, but for "fixes" usually we leave it or reverse precisely.
    }
};
