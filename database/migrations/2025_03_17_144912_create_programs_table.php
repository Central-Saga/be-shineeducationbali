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
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('education_level_id')->constrained('education_levels');
            $table->foreignId('subject_id')->constrained('subjects');
            $table->foreignId('class_type_id')->constrained('class_types');
            $table->foreignId('meeting_frequency_id')->constrained('meeting_frequencies');
            $table->string('program_name', 150);
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->string('sku', 20);
            $table->decimal('freelance_rate_per_session', 10, 2);
            $table->integer('min_parttime_sessions');
            $table->decimal('overtime_rate_per_session', 10, 2);
            $table->enum('status', ['Aktif', 'Non Aktif']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};
