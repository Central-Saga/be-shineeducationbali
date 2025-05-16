<?php

namespace App\Repositories\Eloquent;

use App\Models\Article;
use App\Repositories\Contracts\ArticleRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ArticleRepository implements ArticleRepositoryInterface
{
    /**
     * Instance dari model Article.
     *
     * @var Article
     */
    protected $model;

    /**
     * Konstruktor untuk menginisialisasi model.
     *
     * @param Article $model
     */
    public function __construct(Article $model)
    {
        $this->model = $model;
    }

    /**
     * Mendapatkan semua data article.
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return $this->model->with('user')->get();
    }

    /**
     * Mendapatkan data article berdasarkan ID.
     *
     * @param int $id
     * @return Article|null
     */
    public function findById(int $id): ?Article
    {
        return $this->model->find($id)?->load('user');
    }

    /**
     * Mendapatkan artikel terbaru berdasarkan tanggal publikasi.
     *
     * @param int $limit
     * @return Collection
     */
    public function findRecent(int $limit = 10): Collection
    {
        return $this->model->with('user')
            ->orderBy('publication_date', 'desc')
            ->take($limit)
            ->get();
    }

    /**
     * Membuat data article baru.
     *
     * @param array $data
     * @return Article
     */
    public function create(array $data): Article
    {
        $article = $this->model->create($data);
        return $article->load('user');
    }

    /**
     * Memperbarui data article berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return Article
     */
    public function update(int $id, array $data): Article
    {
        $article = $this->findById($id);
        $article->update($data);
        return $article->fresh()->load('user');
    }

    /**
     * Menghapus data article berdasarkan ID.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $article = $this->findById($id);
        return $article ? $article->delete() : false;
    }
}
