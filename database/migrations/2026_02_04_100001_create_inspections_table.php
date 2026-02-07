<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('inspections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peminjaman_id')->constrained('peminjaman')->onDelete('cascade');
            $table->foreignId('barang_unit_id')->constrained('barang_unit')->onDelete('cascade');
            $table->enum('type', ['pre_borrow', 'post_return']);
            $table->json('checklist_data')->nullable(); // Filled checklist responses
            $table->string('photo_path')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('inspector_id')->constrained('users')->onDelete('cascade');
            $table->boolean('has_damage')->default(false);
            $table->text('damage_details')->nullable();
            $table->timestamp('inspected_at');
            $table->timestamps();

            // Index for quick lookups
            $table->index(['peminjaman_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inspections');
    }
};
