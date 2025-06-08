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
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id(); // Primary Key: id (auto-increment)
            $table->foreignId('vacancy_id') // Foreign Key: vacancy_id
                  ->constrained('job_vacancies') // Merujuk ke tabel job_vacancies
                  ->onUpdate('cascade')
                  ->onDelete('restrict'); // Tidak boleh hapus job vacancy jika ada job application terkait
            $table->foreignId('user_id') // Foreign Key: user_id
                  ->constrained('users') // Merujuk ke tabel users
                  ->onUpdate('cascade')
                  ->onDelete('restrict'); // Tidak boleh hapus user jika ada job application terkait
            $table->dateTime('application_date'); // Kolom application_date (DATETIME)
            $table->enum('status', ['Pending', 'Reviewed', 'Accepted', 'Rejected']); // Kolom status (ENUM)
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
