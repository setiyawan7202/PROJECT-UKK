<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('maintenance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_unit_id')->constrained('barang_unit')->onDelete('cascade');
            $table->foreignId('schedule_id')->nullable()->constrained('maintenance_schedules')->onDelete('set null');
            $table->date('maintenance_date');
            $table->text('description'); // What was done
            $table->string('technician_name');
            $table->foreignId('performed_by')->constrained('users')->onDelete('cascade');
            $table->text('notes')->nullable();
            $table->timestamps();

            // Index for quick lookups
            $table->index('barang_unit_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_logs');
    }
};
