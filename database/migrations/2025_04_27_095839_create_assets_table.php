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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('file_path', 255);
            $table->integer('size_kb');
            $table->dateTime('upload_date');
            $table->string('description', 150)->nullable();
            $table->enum('storage_type', ['local', 'cloud', 's3', 'google_drive']);
            $table->morphs('assetable');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
