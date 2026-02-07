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
        Schema::table('barang_unit', function (Blueprint $table) {
            $table->boolean('is_lost')->default(false)->after('status')->comment('Flag untuk aset yang hilang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barang_unit', function (Blueprint $table) {
            $table->dropColumn('is_lost');
        });
    }
};
