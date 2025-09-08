<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchoolsTable extends Migration {
    public function up() {
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('address');
            $table->string('phone')->nullable(); // Tambah phone supaya padan dengan factory/model
            $table->string('email')->nullable();
            $table->string('ppd')->nullable();
            $table->string('principal_name')->nullable();
            $table->string('principal_phone')->nullable();
            $table->string('hem_name')->nullable();
            $table->string('hem_phone')->nullable();
            $table->string('qr_code')->nullable();
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('schools');
    }
}
