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
        // Using Schema builder with change() since doctrine/dbal is installed
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'active', 'completed', 'rejected', 'overdue', 'canceled'])
                ->default('pending')
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'active', 'completed', 'rejected', 'overdue'])
                ->default('pending')
                ->change();
        });
    }
};
