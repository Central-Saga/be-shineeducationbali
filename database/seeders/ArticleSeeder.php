<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    /**
     * Jalankan seeder untuk mengisi tabel articles.
     *
     * @return void
     */
    public function run(): void
    {
        // Membuat 10 data dummy untuk tabel articles
        Article::factory()->count(10)->create();
    }
}
