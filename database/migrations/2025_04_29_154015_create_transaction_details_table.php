<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained('transactions')->onDelete('cascade');
            $table->foreignId('program_id')->nullable()->constrained('programs')->onDelete('set null');
            $table->foreignId('leave_id')->nullable()->constrained('leaves')->onDelete('set null');
            $table->string('desc', 150);
            $table->decimal('amount', 10, 2);
            $table->enum('type', ['Salary', 'Program_Payment', 'Deduction', 'Expenditure']);
            $table->integer('session_count');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaction_details');
    }
};
