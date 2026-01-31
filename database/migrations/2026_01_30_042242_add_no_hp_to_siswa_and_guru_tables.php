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
        if (!Schema::hasColumn('siswa', 'no_hp')) {
            Schema::table('siswa', function (Blueprint $table) {
                $table->string('no_hp', 20)->nullable()->after('email');
            });
        }

        if (!Schema::hasColumn('guru', 'no_hp')) {
            Schema::table('guru', function (Blueprint $table) {
                $table->string('no_hp', 20)->nullable()->after('email');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->dropColumn('no_hp');
        });

        Schema::table('guru', function (Blueprint $table) {
            $table->dropColumn('no_hp');
        });
    }
};
