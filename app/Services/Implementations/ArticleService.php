<?php

namespace App\Services;

use App\Repositories\ArticleRepositoryInterface;
use App\Models\Article;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class ArticleService implements ArticleServiceInterface
{
    /**
     * Instance dari ArticleRepositoryInterface.
     *
     * @var ArticleRepositoryInterface
     */
    protected $articleRepository;

    /**
     * Konstruktor untuk menginisialisasi repository.
     *
     * @param ArticleRepositoryInterface $articleRepository
     */
    public function __construct(ArticleRepositoryInterface $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    /**
     * Mendapatkan semua data article dengan cache.
     *
     * @return Collection
     */
    public function getAllArticles(): Collection
    {
        return Cache::remember('articles_all', 60 * 60, function () {
            return $this->articleRepository->getAll();
        });
    }

    /**
     * Mendapatkan data article berdasarkan ID.
     *
     * @param int $id
     * @return Article
     */
    public function getArticleById(int $id): Article
    {
        return Cache::remember("article_{$id}", 60 * 60, function () use ($id) {
            return $this->articleRepository->findById($id);
        });
    }

    /**
     * Mendapatkan artikel terbaru berdasarkan tanggal publikasi.
     *
     * @param int $limit
     * @return Collection
     */
    public function getRecentArticles(int $limit = 10): Collection
    {
        return Cache::remember("articles_recent_{$limit}", 60 * 60, function () use ($limit) {
            return $this->articleRepository->findRecent($limit);
        });
    }

    /**
     * Membuat data article baru dan menghapus cache terkait.
     *
     * @param array $data
     * @return Article
     */
    public function createArticle(array $data): Article
    {
        $article = $this->articleRepository->create($data);
        $this->clearCache();
        return $article;
    }

    /**
     * Memperbarui data article berdasarkan ID dan menghapus cache terkait.
     *
     * @param int $id
     * @param array $data
     * @return Article
     */
    public function updateArticle(int $id, array $data): Article
    {
        $article = $this->articleRepository->update($id, $data);
        $this->clearCache();
        return $article;
    }

    /**
     * Menghapus data article berdasarkan ID dan menghapus cache terkait.
     *
     * @param int $id
     * @return bool
     */
    public function deleteArticle(int $id): bool
    {
        $result = $this->articleRepository->delete($id);
        $this->clearCache();
        return $result;
    }

    /**
     * Menghapus cache terkait data article.
     *
     * @return void
     */
    protected function clearCache(): void
    {
        Cache::forget('articles_all');
        Cache::forget('articles_recent_10'); // Sesuaikan dengan limit default
        // Untuk efisiensi, kita bisa menghapus cache spesifik berdasarkan ID,
        // tetapi untuk saat ini kita hapus semua cache terkait article.
        Cache::tags(['articles'])->flush();
    }
}
