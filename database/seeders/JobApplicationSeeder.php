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

        // Membuat 10 job applications
        foreach ($users as $user) {
            foreach ($jobVacancies as $jobVacancy) {
                JobApplication::factory()->create([
                    'user_id' => $user->id,
                    'vacancy_id' => $jobVacancy->id,
                ]);
            }
        }
    }
}
