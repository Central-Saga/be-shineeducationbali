<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Student;

class StudentSeeder extends Seeder
{
    /**
     * Mengisi basis data dengan data awal untuk tabel students.
     */
    public function run(): void
    {
        // Membuat 10 data student menggunakan factory
        Student::factory()->count(5)->create();
    }
}
