<?php

namespace Database\Factories;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Notification>
 */
class NotificationFactory extends Factory
{
    /**
     * Model yang terkait dengan factory ini.
     *
     * @var string
     */
    protected $model = Notification::class;

    /**
     * Definisikan data default untuk model Notification.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = $this->faker->randomElement(['Payment', 'Leave', 'Grade']);

        // Tentukan pesan berdasarkan tipe notifikasi
        $message = match ($type) {
            'Payment' => 'Selamat, pembayaran Anda untuk kursus ' . $this->faker->word() . ' telah berhasil.',
            'Leave' => 'Pengajuan cuti Anda pada tanggal ' . $this->faker->date() . ' telah ' . $this->faker->randomElement(['disetujui', 'ditolak']) . '.',
            'Grade' => 'Nilai ujian Anda untuk mata pelajaran ' . $this->faker->word() . ' telah tersedia: ' . $this->faker->numberBetween(70, 100) . '.',
        };

        return [
            'user_id' => User::factory(), // Membuat user baru secara otomatis
            'type' => $type, // Tipe notifikasi: Payment, Leave, atau Grade
            'message' => $message, // Pesan sesuai tipe notifikasi
            'sent_date' => $this->faker->dateTimeBetween('-1 month', 'now'), // Tanggal pengiriman acak dalam 1 bulan terakhir
            'status' => $this->faker->randomElement(['read', 'unread']), // Status acak: read atau unread
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
