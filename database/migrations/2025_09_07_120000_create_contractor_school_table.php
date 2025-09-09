<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('contractor_school', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contractor_id')->constrained('contractors')->cascadeOnDelete();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['contractor_id', 'school_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('contractor_school');
    }
};
