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
        Schema::create('teachers', function (Blueprint $table) {
            // Primary key
            $table->id();

            // Foreign keys
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('user_id');

            // Enum field for employee_type
            $table->enum('employee_type', ['parttime', 'fulltime', 'freelance']);

            // Other fields
            $table->decimal('monthly_salary', 10, 2); // Assuming 2 decimal places for salary
            $table->enum('status', ['Aktif', 'Non Aktif']); // Assuming status enum, adjust as needed
            $table->timestamps(); // Adds created_at and updated_at

            // Define foreign key constraints
            $table->foreign('subject_id')
                  ->references('id')
                  ->on('subjects')
                  ->onDelete('cascade'); // Assuming subjects table exists

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade'); // Assuming users table exists
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};