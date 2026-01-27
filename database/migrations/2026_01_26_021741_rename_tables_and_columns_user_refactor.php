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
        Schema::rename('siswas', 'siswa');
        Schema::rename('gurus', 'guru');

        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('username', 'data_nip_nisn');
        });

        Schema::table('siswa', function (Blueprint $table) {
            $table->string('username')->after('nisn'); // Stores nama_lengkap
        });

        Schema::table('guru', function (Blueprint $table) {
            $table->string('username')->after('nip'); // Stores nama_lengkap
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('guru', function (Blueprint $table) {
            $table->dropColumn('username');
        });

        Schema::table('siswa', function (Blueprint $table) {
            $table->dropColumn('username');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('data_nip_nisn', 'username');
        });

        Schema::rename('guru', 'gurus');
        Schema::rename('siswa', 'siswas');
    }
};
