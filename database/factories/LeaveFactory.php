<?php

namespace Database\Factories;

use App\Models\Leave;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Leave>
 */
class LeaveFactory extends Factory
{
    /**
     * Model yang terkait dengan factory ini.
     *
     * @var string
     */
    protected $model = Leave::class;

    /**
     * Definisikan data default untuk model Leave.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(), // Membuat user baru secara otomatis
            'start_date' => $this->faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d'), // Tanggal mulai acak dalam 1 bulan terakhir
            'end_date' => $this->faker->dateTimeBetween('now', '+1 month')->format('Y-m-d'), // Tanggal selesai acak dalam 1 bulan ke depan
            'reason' => $this->faker->randomElement(['sick', 'personal', 'other']), // Alasan acak dari opsi yang tersedia
            'status' => $this->faker->randomElement(['disetujui', 'ditolak', 'menunggu konfirmasi']), // Status acak dari opsi yang tersedia
            'user_type' => $this->faker->randomElement(['student', 'teacher']), // Tipe user acak dari opsi yang tersedia
            'deduction_amount' => $this->faker->optional()->randomFloat(2, 100000, 1000000), // Nominal potongan acak (nullable)
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
