<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Barang;
use App\Models\BarangUnit;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('barang', function (Blueprint $table) {
            $table->string('kode', 10)->nullable()->after('id');
        });

        // Update existing barang dengan kode dari unit pertama atau generate dari nama
        foreach (Barang::all() as $barang) {
            $firstUnit = $barang->units()->first();
            if ($firstUnit) {
                $kode = explode('-', $firstUnit->kode_unit)[0];
            } else {
                $kode = BarangUnit::generatePrefix($barang->nama_barang);
            }
            $barang->update(['kode' => $kode]);
        }
    }

    public function down(): void
    {
        Schema::table('barang', function (Blueprint $table) {
            $table->dropColumn('kode');
        });
    }
};
