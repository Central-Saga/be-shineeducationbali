<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk membuat tabel leaves.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('leaves', function (Blueprint $table) {
            $table->id(); // Kolom id sebagai primary key
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Kolom user_id sebagai foreign key ke tabel users
            $table->date('start_date'); // Kolom tanggal mulai cuti
            $table->date('end_date'); // Kolom tanggal selesai cuti
            $table->enum('reason', ['sick', 'personal', 'other']); // Kolom alasan cuti, hanya sick, personal, atau other
            $table->enum('status', ['disetujui', 'ditolak', 'menunggu konfirmasi'])->default('menunggu konfirmasi'); // Kolom status cuti
            $table->enum('user_type', ['student', 'teacher']); // Kolom user_type, hanya student atau teacher
            $table->decimal('deduction_amount', 10, 2)->nullable(); // Kolom potongan gaji, nullable
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Batalkan migrasi dengan menghapus tabel leaves.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('leaves');
    }
};
