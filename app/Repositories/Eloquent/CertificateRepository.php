<?php

namespace App\Repositories;

use App\Models\Certificate;
use App\Repositories\Interfaces\CertificateRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CertificateRepository implements CertificateRepositoryInterface
{
    protected $model;

    public function __construct(Certificate $model)
    {
        $this->model = $model;
    }

    /**
     * Mendapatkan semua data certificate.
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return $this->model->with(['student', 'program'])->get();
    }

    /**
     * Mendapatkan certificate berdasarkan ID.
     *
     * @param int $id
     * @return Certificate|null
     */
    public function findById(int $id): ?Certificate
    {
        return $this->model->with(['student', 'program'])->findOrFail($id);
    }

    /**
     * Membuat certificate baru.
     *
     * @param array $data
     * @return Certificate
     */
    public function create(array $data): Certificate
    {
        return $this->model->create($data);
    }

    /**
     * Memperbarui certificate berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return Certificate
     */
    public function update(int $id, array $data): Certificate
    {
        $certificate = $this->model->findOrFail($id);
        $certificate->update($data);
        return $certificate;
    }

    /**
     * Menghapus certificate berdasarkan ID.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $certificate = $this->model->findOrFail($id);
        return $certificate->delete();
    }
}
