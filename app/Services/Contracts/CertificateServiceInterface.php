<?php

namespace App\Services\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use App\Models\Certificate;

interface CertificateServiceInterface
{
    /**
     * Mengambil semua data certificate.
     *
     * @return Collection
     */
    public function getAllCertificates(): Collection;

    /**
     * Mengambil certificate berdasarkan ID.
     *
     * @param int $id
     * @return Certificate
     */
    public function getCertificateById(int $id): Certificate;

    /**
     * Membuat certificate baru.
     *
     * @param array $data
     * @return Certificate
     */
    public function createCertificate(array $data): Certificate;

    /**
     * Memperbarui certificate berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return Certificate
     */
    public function updateCertificate(int $id, array $data): Certificate;

    /**
     * Menghapus certificate berdasarkan ID.
     *
     * @param int $id
     * @return bool
     */
    public function deleteCertificate(int $id): bool;
}
