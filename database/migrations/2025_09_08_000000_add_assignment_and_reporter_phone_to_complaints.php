<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAssignmentAndReporterPhoneToComplaints extends Migration
{
    public function up()
    {
        Schema::table('complaints', function (Blueprint $table) {
            // store reporter phone at time of report (nullable)
            if (! Schema::hasColumn('complaints', 'reporter_phone')) {
                $table->string('reporter_phone')->nullable()->after('reported_at');
            }

            // who assigned the complaint (user id)
            if (! Schema::hasColumn('complaints', 'assigned_by')) {
                $table->foreignId('assigned_by')->nullable()->constrained('users')->onDelete('set null')->after('assigned_to');
            }

            // explicit timestamp for when assignment occurred
            if (! Schema::hasColumn('complaints', 'assigned_at')) {
                $table->timestamp('assigned_at')->nullable()->after('assigned_by');
            }
        });
    }

    public function down()
    {
        Schema::table('complaints', function (Blueprint $table) {
            if (Schema::hasColumn('complaints', 'assigned_at')) {
                $table->dropColumn('assigned_at');
            }
            if (Schema::hasColumn('complaints', 'assigned_by')) {
                $table->dropForeign(['assigned_by']);
                $table->dropColumn('assigned_by');
            }
            if (Schema::hasColumn('complaints', 'reporter_phone')) {
                $table->dropColumn('reporter_phone');
            }
        });
    }
}
