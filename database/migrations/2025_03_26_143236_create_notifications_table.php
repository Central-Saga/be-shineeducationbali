<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk membuat tabel notifications.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id(); // Kolom id sebagai primary key
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Kolom user_id sebagai foreign key ke tabel users
            $table->enum('type', ['Payment', 'Leave', 'Grade']); // Kolom type, enum dengan opsi Payment, Leave, Grade
            $table->text('message'); // Kolom message, tipe text untuk panjang maksimal 65535 karakter
            $table->dateTime('sent_date'); // Kolom sent_date, tipe datetime
            $table->enum('status', ['read', 'unread'])->default('unread'); // Kolom status, enum dengan nilai read/unread, default unread
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Batalkan migrasi dengan menghapus tabel notifications.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
