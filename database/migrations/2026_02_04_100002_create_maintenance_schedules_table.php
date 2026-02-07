<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('maintenance_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->nullable()->constrained('kategori')->onDelete('cascade');
            $table->foreignId('barang_id')->nullable()->constrained('barang')->onDelete('cascade');
            $table->integer('interval_days'); // Days between maintenance (e.g., 90 = 3 months)
            $table->date('next_maintenance_at')->nullable();
            $table->json('reminder_days')->default('[7, 3, 0]'); // Days before to remind
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_schedules');
    }
};
