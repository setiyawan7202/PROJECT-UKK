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
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->string('kode')->nullable()->unique()->after('id');
        });

        Schema::table('pengaduan', function (Blueprint $table) {
            $table->string('kode')->nullable()->unique()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropColumn('kode');
        });

        Schema::table('pengaduan', function (Blueprint $table) {
            $table->dropColumn('kode');
        });
    }
};
