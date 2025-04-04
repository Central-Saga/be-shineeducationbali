<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('job_vacancies', function (Blueprint $table) {
            $table->id(); // Primary Key: id (auto-increment)
            $table->foreignId('subject_id') // Foreign Key: subject_id
                  ->constrained('subjects') // Merujuk ke tabel subjects
                  ->onUpdate('cascade')
                  ->onDelete('restrict'); // Tidak boleh hapus subject jika ada job vacancy terkait
            $table->string('title'); // Kolom title (VARCHAR)
            $table->text('description'); // Kolom description (TEXT)
            $table->decimal('salary', 10, 2); // Kolom salary (DECIMAL, misal 10 digit dengan 2 desimal)
            $table->date('application_deadline'); // Kolom application_deadline (DATE)
            $table->enum('status', ['Open', 'Closed']); // Kolom status (ENUM: Open, Closed)
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_vacancies');
    }
};
