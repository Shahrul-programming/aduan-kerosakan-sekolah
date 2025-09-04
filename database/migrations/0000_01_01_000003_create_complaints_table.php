<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplaintsTable extends Migration {
    public function up() {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->string('complaint_number')->unique();
            $table->string('title'); // Tambah column title
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('reported_by')->constrained('users')->onDelete('cascade'); // Tambah reported_by
            $table->string('category');
            $table->text('description');
            $table->string('image')->nullable();
            $table->string('video')->nullable();
            $table->enum('priority', ['tinggi', 'sederhana', 'rendah', 'urgent']); // Tambah 'urgent'
            $table->enum('status', ['baru', 'semakan', 'assigned', 'proses', 'selesai', 'pending', 'in_progress', 'completed']); // Tambah status seeder
            $table->timestamp('reported_at')->nullable(); // Tambah reported_at
            $table->foreignId('assigned_to')->nullable()->constrained('contractors')->onDelete('set null');
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('complaints');
    }
}
