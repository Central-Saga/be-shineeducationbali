<?php

namespace App\Repositories\Contracts;

use App\Models\Article;
use Illuminate\Database\Eloquent\Collection;

interface ArticleRepositoryInterface
{
    /**
     * Mendapatkan semua data article.
     *
     * @return Collection
     */
    public function getAll(): Collection;

    /**
     * Mendapatkan data article berdasarkan ID.
     *
     * @param int $id
     * @return Article|null
     */
    public function findById(int $id): ?Article;

    /**
     * Mendapatkan artikel terbaru berdasarkan tanggal publikasi.
     *
     * @param int $limit
     * @return Collection
     */
    public function findRecent(int $limit = 10): Collection;

    /**
     * Membuat data article baru.
     *
     * @param array $data
     * @return Article
     */
    public function create(array $data): Article;

    /**
     * Memperbarui data article berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return Article
     */
    public function update(int $id, array $data): Article;

    /**
     * Menghapus data article berdasarkan ID.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;
}
