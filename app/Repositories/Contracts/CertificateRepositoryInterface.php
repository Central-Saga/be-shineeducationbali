<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use App\Models\Certificate;

interface CertificateRepositoryInterface
{
    /**
     * Mendapatkan semua data certificate.
     *
     * @return Collection
     */
    public function getAll(): Collection;

    /**
     * Mendapatkan certificate berdasarkan ID.
     *
     * @param int $id
     * @return Certificate|null
     */
    public function findById(int $id): ?Certificate;

    /**
     * Membuat certificate baru.
     *
     * @param array $data
     * @return Certificate
     */
    public function create(array $data): Certificate;

    /**
     * Memperbarui certificate berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return Certificate
     */
    public function update(int $id, array $data): Certificate;

    /**
     * Menghapus certificate berdasarkan ID.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;
}
