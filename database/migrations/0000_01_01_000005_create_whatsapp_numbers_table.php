<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('whatsapp_numbers', function (Blueprint $table) {
            $table->id();
            $table->string('number');
            $table->enum('status', ['active', 'inactive']);
            $table->string('qr_code')->nullable();
            $table->text('session_data')->nullable();
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('whatsapp_numbers');
    }
};
