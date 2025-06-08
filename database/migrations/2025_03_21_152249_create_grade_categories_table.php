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
        Schema::create('grade_categories', function (Blueprint $table) {
            $table->id(); // Kolom 'id' sebagai Primary Key (auto-incrementing integer)
            $table->foreignId('program_id')->constrained()->onDelete('cascade'); // Kolom 'program_id' sebagai Foreign Key
            $table->string('category_name', 100); // Kolom 'category_name' dengan panjang maksimum 100 karakter
            $table->text('description')->nullable(); // Kolom 'description' sebagai text, nullable
            $table->timestamps(); // Kolom 'created_at' dan 'updated_at' untuk timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grade_categories');
    }
};
