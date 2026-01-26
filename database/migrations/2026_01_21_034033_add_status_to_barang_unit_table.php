<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('barang_unit', function (Blueprint $table) {
            // aktif = normal, maintenance = sedang diperbaiki, rusak = tidak bisa diperbaiki
            $table->enum('status', ['aktif', 'maintenance', 'rusak'])->default('aktif')->after('kondisi');
        });
    }

    public function down(): void
    {
        Schema::table('barang_unit', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
