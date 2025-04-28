<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk membuat tabel.
     */
    public function up(): void
    {
        Schema::create('testimonials', function (Blueprint $table) {
            // Kolom id sebagai primary key
            $table->id();

            // Kolom foreign key untuk student_id
            $table->foreignId('student_id')
                  ->constrained('students') // Merujuk ke tabel students
                  ->onDelete('cascade');   // Jika data di tabel students dihapus, data di tabel testimonials juga dihapus

            // Kolom content
            $table->text('content');

            // Kolom timestamps untuk created_at dan updated_at
            $table->timestamps();
        });
    }

    /**
     * Batalkan migrasi dengan menghapus tabel.
     */
    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
