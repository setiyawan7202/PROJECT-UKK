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
        Schema::rename('kategoris', 'kategori');
        Schema::rename('ruangans', 'ruangan');
        Schema::rename('barangs', 'barang');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('kategori', 'kategoris');
        Schema::rename('ruangan', 'ruangans');
        Schema::rename('barang', 'barangs');
    }
};
