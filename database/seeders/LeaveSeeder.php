<?php

namespace Database\Seeders;

use App\Models\Leave;
use Illuminate\Database\Seeder;

class LeaveSeeder extends Seeder
{
    /**
     * Jalankan seeder untuk mengisi tabel leaves.
     *
     * @return void
     */
    public function run(): void
    {
        // Membuat 10 data dummy untuk tabel leaves
        Leave::factory()->count(10)->create();
    }
}
