<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Model yang terkait dengan factory ini.
     *
     * @var string
     */
    protected $model = Article::class;

    /**
     * Definisikan data default untuk model Article.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(), // Membuat user baru secara otomatis
            'title' => $this->faker->sentence(5), // Judul artikel acak dengan 5 kata
            'content' => $this->faker->paragraphs(3, true), // Isi artikel acak dengan 3 paragraf
            'publication_date' => $this->faker->dateTimeBetween('-1 month', 'now'), // Tanggal publikasi acak dalam 1 bulan terakhir
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
