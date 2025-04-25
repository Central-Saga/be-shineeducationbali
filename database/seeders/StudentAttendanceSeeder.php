<?php

namespace Database\Seeders;

use App\Models\StudentAttendance;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentAttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat 200 data absensi siswa menggunakan factory
        StudentAttendance::factory(200)->create();
    }
}
