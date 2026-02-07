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
        Schema::table('ruangan', function (Blueprint $table) {
            $table->foreignId('kepala1_id')->nullable()->after('keterangan')->constrained('users')->nullOnDelete();
            $table->foreignId('kepala2_id')->nullable()->after('kepala1_id')->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ruangan', function (Blueprint $table) {
            $table->dropForeign(['kepala1_id']);
            $table->dropForeign(['kepala2_id']);
            $table->dropColumn(['kepala1_id', 'kepala2_id']);
        });
    }
};
