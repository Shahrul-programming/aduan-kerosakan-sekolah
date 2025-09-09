<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSchoolIdToContractorsTable extends Migration
{
    public function up()
    {
        Schema::table('contractors', function (Blueprint $table) {
            $table->foreignId('school_id')->nullable()->after('id')->constrained('schools')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('contractors', function (Blueprint $table) {
            $table->dropConstrainedForeignId('school_id');
        });
    }
}
