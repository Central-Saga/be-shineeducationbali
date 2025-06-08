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
        Schema::create('students', function (Blueprint $table) {
            // Kolom id sebagai primary key
            $table->id();

            // Kolom foreign key untuk program_id
            $table->foreignId('program_id')
                  ->constrained('programs') // Merujuk ke tabel programs
                  ->onDelete('cascade');   // Jika data di tabel programs dihapus, data di tabel students juga dihapus

            // Kolom foreign key untuk user_id
            $table->foreignId('user_id')    
                  ->constrained('users')   // Merujuk ke tabel users
                  ->onDelete('cascade');   // Jika data di tabel users dihapus, data di tabel students juga dihapus

            // Kolom start_date
            $table->date('start_date');

            // Kolom end_date
            $table->date('end_date');

            // Kolom registration_date
            $table->date('registration_date');

            // Kolom status dengan tipe enum
            $table->enum('status', ['Aktif', 'Non Aktif']); // Contoh nilai enum, sesuaikan dengan kebutuhan

            // Kolom timestamps untuk created_at dan updated_at
            $table->timestamps();
        });
    }

    /**
     * Batalkan migrasi dengan menghapus tabel.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
