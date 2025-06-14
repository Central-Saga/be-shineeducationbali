<?php

namespace Database\Factories;

use App\Models\ClassRoom;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Schedule>
 */
class ScheduleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Cek apakah ada data ClassRoom
        $classRoom = ClassRoom::count() > 0 ? ClassRoom::inRandomOrder()->first() : ClassRoom::factory()->create();

        return [
            'class_room_id' => $classRoom->id,
            'day' => fake()->randomElement(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu']),
            'start_time' => fake()->time(),
            'end_time' => fake()->time(),
        ];
    }
}
