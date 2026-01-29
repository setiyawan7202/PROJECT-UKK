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
        Schema::create('pengaduan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('judul');
            $table->text('deskripsi');
            $table->string('lokasi'); // e.g., "Lab Komputer 1"
            $table->string('jenis_sarpras')->nullable(); // e.g., "Proyektor", or category ID if needed
            $table->enum('status', ['pending', 'processed', 'completed', 'rejected'])->default('pending');
            $table->string('foto')->nullable(); // Path to image
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('catatan_pengaduan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengaduan_id')->constrained('pengaduan')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Who made the note (usually admin/staff)
            $table->text('catatan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catatan_pengaduan');
        Schema::dropIfExists('pengaduan');
    }
};
