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
        // Modify the enum to include 'teacher'
        // Include all existing role variants present in the DB to avoid
        // data truncation errors. We add 'teacher' but keep current values.
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('super_admin','pengurusan','guru','kontraktor','school_admin','contractor','technician','teacher') NOT NULL");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to previous enum (remove 'teacher')
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('super_admin','pengurusan','guru','kontraktor','school_admin','contractor','technician') NOT NULL");
        }
    }
};
