<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop siswa first (has foreign key to kelas)
        Schema::dropIfExists('siswa');

        // Drop guru
        Schema::dropIfExists('guru');

        // Drop kelas
        Schema::dropIfExists('kelas');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate kelas table
        Schema::create('kelas', function ($table) {
            $table->id();
            $table->string('nama_kelas');
            $table->string('jurusan');
            $table->string('tingkat');
            $table->timestamps();
        });

        // Recreate guru table
        Schema::create('guru', function ($table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nama_lengkap');
            $table->string('email');
            $table->string('nip')->unique();
            $table->timestamps();
        });

        // Recreate siswa table
        Schema::create('siswa', function ($table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->string('nama_lengkap');
            $table->string('email');
            $table->string('nisn')->unique();
            $table->timestamps();
        });
    }
};
