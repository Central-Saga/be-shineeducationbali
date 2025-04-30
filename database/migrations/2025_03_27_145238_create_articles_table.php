<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk membuat tabel articles.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id(); // Kolom id sebagai primary key
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Kolom user_id sebagai foreign key ke tabel users
            $table->string('title', 150); // Kolom title, varchar dengan panjang maksimal 150 karakter
            $table->text('content'); // Kolom content, tipe text untuk panjang maksimal 65535 karakter
            $table->dateTime('publication_date'); // Kolom publication_date, tipe datetime
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Batalkan migrasi dengan menghapus tabel articles.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
