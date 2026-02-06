<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modify enum to add superadmin role
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('superadmin', 'admin', 'petugas', 'pengguna') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove superadmin from enum
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'petugas', 'pengguna') NOT NULL");
    }
};
