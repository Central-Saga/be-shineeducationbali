<?php

namespace App\Services;

use App\Models\Article;
use Illuminate\Database\Eloquent\Collection;

interface ArticleServiceInterface
{
    /**
     * Mendapatkan semua data article.
     *
     * @return Collection
     */
    public function getAllArticles(): Collection;

    /**
     * Mendapatkan data article berdasarkan ID.
     *
     * @param int $id
     * @return Article
     */
    public function getArticleById(int $id): Article;

    /**
     * Mendapatkan artikel terbaru berdasarkan tanggal publikasi.
     *
     * @param int $limit
     * @return Collection
     */
    public function getRecentArticles(int $limit = 10): Collection;

    /**
     * Membuat data article baru.
     *
     * @param array $data
     * @return Article
     */
    public function createArticle(array $data): Article;

    /**
     * Memperbarui data article berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return Article
     */
    public function updateArticle(int $id, array $data): Article;

    /**
     * Menghapus data article berdasarkan ID.
     *
     * @param int $id
     * @return bool
     */
    public function deleteArticle(int $id): bool;
}
