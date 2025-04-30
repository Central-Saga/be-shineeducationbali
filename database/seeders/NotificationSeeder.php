<?php

namespace Database\Seeders;

use App\Models\Notification;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Jalankan seeder untuk mengisi tabel notifications.
     *
     * @return void
     */
    public function run(): void
    {
        // Membuat 10 data dummy untuk tabel notifications
        Notification::factory()->count(10)->create();
    }
}  
