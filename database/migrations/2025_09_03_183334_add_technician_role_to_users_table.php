<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, check and clean any invalid role data
        DB::statement("UPDATE users SET role = 'school_admin' WHERE role NOT IN ('super_admin', 'school_admin', 'contractor')");

        // Then modify the enum to include technician (skip raw ALTER on sqlite)
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('super_admin', 'school_admin', 'contractor', 'technician') NOT NULL DEFAULT 'school_admin'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // First update any technician roles to school_admin
        DB::statement("UPDATE users SET role = 'school_admin' WHERE role = 'technician'");

        // Then revert the enum (skip raw ALTER on sqlite)
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('super_admin', 'school_admin', 'contractor') NOT NULL DEFAULT 'school_admin'");
        }
    }
};
