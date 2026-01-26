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
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            // barang_id is the generic item requested
            $table->foreignId('barang_id')->constrained('barang')->onDelete('cascade');
            // barang_unit_id is the specific unit assigned (nullable initially)
            $table->foreignId('barang_unit_id')->nullable()->constrained('barang_unit')->onDelete('set null');

            $table->integer('jumlah')->default(1);
            $table->date('tgl_pinjam');
            $table->date('tgl_kembali_rencana');
            $table->date('tgl_kembali_aktual')->nullable();
            $table->string('tujuan_pinjam');

            // Status flow: pending -> approved (assigned unit) -> active (taken) -> completed (returned)
            // exceeded/overdue logic handled by logic/query
            $table->enum('status', ['pending', 'approved', 'active', 'completed', 'rejected', 'overdue'])->default('pending');
            $table->string('keterangan_penolakan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};
