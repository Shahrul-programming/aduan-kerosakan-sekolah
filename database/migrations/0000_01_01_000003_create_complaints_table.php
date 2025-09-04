<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplaintsTable extends Migration {
    public function up() {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->string('complaint_number')->unique();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('category');
            $table->text('description');
            $table->string('image')->nullable();
            $table->string('video')->nullable();
            $table->enum('priority', ['tinggi', 'sederhana', 'rendah']);
            $table->enum('status', ['baru', 'semakan', 'assigned', 'proses', 'selesai']);
            $table->foreignId('assigned_to')->nullable()->constrained('contractors')->onDelete('set null');
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('complaints');
    }
}
