<?php

namespace Database\Seeders;

use App\Models\JobApplication;
use App\Models\JobVacancy;
use App\Models\User;
use Illuminate\Database\Seeder;

class JobApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan ada user dan job vacancy
        $users = User::all();
        $jobVacancies = JobVacancy::all();

        if ($users->isEmpty() || $jobVacancies->isEmpty()) {
            // Jika tidak ada data, buat beberapa user dan job vacancy terlebih dahulu
            $users = User::factory()->count(5)->create();
            $jobVacancies = JobVacancy::factory()->count(3)->create();
        }

        // Status yang tersedia dengan mapping angka ke string
        $statusMap = [
            '1' => 'Pending',
            '2' => 'Reviewed',
            '3' => 'Accepted',
            '0' => 'Rejected'
        ];

        // Membuat job applications dengan status yang berbeda-beda
        foreach ($users as $user) {
            foreach ($jobVacancies as $jobVacancy) {
                foreach ($statusMap as $numericStatus => $stringStatus) {
                    JobApplication::create([
                        'user_id' => $user->id,
                        'vacancy_id' => $jobVacancy->id,
                        'application_date' => now(),
                        'status' => $stringStatus
                    ]);
                }
            }
        }
    }
}
