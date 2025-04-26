<?php

namespace App\Services;

use App\Repositories\Interfaces\CertificateRepositoryInterface;
use App\Services\Interfaces\CertificateServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Certificate;

class CertificateService implements CertificateServiceInterface
{
    protected $certificateRepository;

    public function __construct(CertificateRepositoryInterface $certificateRepository)
    {
        $this->certificateRepository = $certificateRepository;
    }

    /**
     * Mengambil semua data certificate.
     *
     * @return Collection
     */
    public function getAllCertificates(): Collection
    {
        return $this->certificateRepository->getAll();
    }

    /**
     * Mengambil certificate berdasarkan ID.
     *
     * @param int $id
     * @return Certificate
     */
    public function getCertificateById(int $id): Certificate
    {
        return $this->certificateRepository->findById($id);
    }

    /**
     * Membuat certificate baru.
     *
     * @param array $data
     * @return Certificate
     */
    public function createCertificate(array $data): Certificate
    {
        return $this->certificateRepository->create($data);
    }

    /**
     * Memperbarui certificate berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return Certificate
     */
    public function updateCertificate(int $id, array $data): Certificate
    {
        return $this->certificateRepository->update($id, $data);
    }

    /**
     * Menghapus certificate berdasarkan ID.
     *
     * @param int $id
     * @return bool
     */
    public function deleteCertificate(int $id): bool
    {
        return $this->certificateRepository->delete($id);
    }
}
