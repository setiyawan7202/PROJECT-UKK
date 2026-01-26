<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('barang', function (Blueprint $table) {
            $table->dropColumn(['kode_barang', 'kondisi_saat_ini']);
        });
    }

    public function down(): void
    {
        Schema::table('barang', function (Blueprint $table) {
            $table->string('kode_barang')->unique()->after('id');
            $table->string('kondisi_saat_ini')->after('jumlah_stok');
        });
    }
};
