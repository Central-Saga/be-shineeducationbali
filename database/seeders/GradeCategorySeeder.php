<?php

namespace Database\Seeders;

use App\Models\GradeCategory;
use App\Models\Program;
use App\Models\EducationLevel;
use App\Models\Subject;
use App\Models\ClassType;
use App\Models\MeetingFrequency;
use Illuminate\Database\Seeder;

class GradeCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan ada data yang dibutuhkan
        $educationLevel = EducationLevel::first();
        $subject = Subject::first();
        $classType = ClassType::first();
        $meetingFrequency = MeetingFrequency::first();
        
        // Jika tidak ada data yang dibutuhkan, hentikan proses
        if (!$educationLevel || !$subject || !$classType || !$meetingFrequency) {
            $this->command->error('Tidak dapat membuat Program karena data yang dibutuhkan tidak tersedia!');
            return;
        }
        
        // Cari program dengan nama 'Default Program' atau buat jika tidak ada
        $program = Program::firstOrCreate(
            ['program_name' => 'Default Program'],
            [
                'education_level_id' => $educationLevel->id,
                'subject_id' => $subject->id,
                'class_type_id' => $classType->id,
                'meeting_frequency_id' => $meetingFrequency->id,
                'description' => 'Program default untuk grade categories',
                'price' => 1000000,
                'sku' => 'DEF-PROG-001',
                'freelance_rate_per_session' => 100000,
                'min_parttime_sessions' => 5,
                'overtime_rate_per_session' => 120000,
                'status' => 'Aktif'
            ]
        );

        // Membuat beberapa GradeCategory untuk Program tersebut
        GradeCategory::factory()->create([
            'program_id' => $program->id,
            'category_name' => 'Good',
            'description' => 'Score range: 70-79',
        ]);

        GradeCategory::factory()->create([
            'program_id' => $program->id,
            'category_name' => 'Very Good',
            'description' => 'Score range: 80-89',
        ]);

        GradeCategory::factory()->create([
            'program_id' => $program->id,
            'category_name' => 'Excellent',
            'description' => 'Score range: 90-100',
        ]);
    }
}
