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
        Schema::create('certificates', function (Blueprint $table) {
            $table->id(); // Primary Key: id (auto-incrementing integer)

            // Foreign Key: student_id (references the id column in the students table)
            $table->foreignId('student_id')
                  ->constrained('students')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            // Foreign Key: program_id (references the id column in the programs table)
            $table->foreignId('program_id')
                  ->constrained('programs')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->date('issue_date'); // Issue date of the certificate
            $table->timestamps(); // Timestamps for created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
