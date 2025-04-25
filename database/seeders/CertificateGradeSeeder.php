<?php

namespace Database\Seeders;

use App\Models\CertificateGrade;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CertificateGradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat 50 data nilai sertifikat menggunakan factory
        CertificateGrade::factory(50)->create();
    }
}
