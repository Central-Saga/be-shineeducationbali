<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a super admin user using the User factory
        $superAdmin = User::factory()->create([
            'name'  => 'Super Admin',
            'email' => 'superadmin@example.com',
        ]);

        // Assign the Super Admin role
        $superAdmin->assignRole('Super Admin');
        // Remove the user from the default role
        $superAdmin->removeRole('Student');

        // Create a teacher user using the User factory
        $teacher = User::factory()->create([
            'name'  => 'Teacher',
            'email' => 'teacher@example.com',
        ]);

        // Assign the Teacher role
        $teacher->assignRole('Teacher');
        // Remove the user from the default role
        $teacher->removeRole('Student');

        // Create a student user using the User factory
        $student = User::factory()->create([
            'name'  => 'Student',
            'email' => 'student@example.com',
        ]);

        // Assign the Student role
        $student->assignRole('Student');
    }
}
