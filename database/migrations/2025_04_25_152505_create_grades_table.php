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
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students');
            $table->foreignId('class_rooms_id')->constrained('class_rooms');
            $table->foreignId('material_id')->constrained('materials');
            $table->foreignId('assignment_id')->constrained('assignments');
            $table->foreignId('grade_category_id')->constrained('grade_categories');
            $table->decimal('score', 8, 2);
            $table->date('input_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
