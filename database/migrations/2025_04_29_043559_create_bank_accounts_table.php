<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('bank_name', 50);
            $table->string('account_number', 50);
            $table->string('account_holder', 100);
            $table->enum('account_type', ['Receipt', 'Expenditure']);
            $table->enum('status', ['Aktif', 'Non Aktif']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bank_accounts');
    }
};
