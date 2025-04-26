<?php

namespace App\Services\Implementations;

use App\Services\Contracts\CertificateServiceInterface;
use App\Repositories\Contracts\CertificateRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class CertificateService implements CertificateServiceInterface
{
    protected $repository;

    // Cache keys
    const ALL_CERTIFICATES_CACHE_KEY = 'all_certificates';
    const CERTIFICATE_BY_ID_CACHE_KEY = 'certificate_by_id_';
    const CERTIFICATES_BY_STUDENT_CACHE_KEY = 'certificates_by_student_';
    const CERTIFICATES_BY_PROGRAM_CACHE_KEY = 'certificates_by_program_';

    public function __construct(CertificateRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Mengambil semua sertifikat.
     *
     * @return mixed
     */
    public function getAllCertificates()
    {
        return Cache::remember(self::ALL_CERTIFICATES_CACHE_KEY, 3600, function () {
            return $this->repository->getAllCertificates();
        });
    }

    /**
     * Mengambil sertifikat berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getCertificateById($id)
    {
        return Cache::remember(self::CERTIFICATE_BY_ID_CACHE_KEY . $id, 3600, function () use ($id) {
            return $this->repository->getCertificateById($id);
        });
    }

    /**
     * Mengambil sertifikat berdasarkan ID siswa.
     *
     * @param int $studentId
     * @return mixed
     */
    public function getCertificatesByStudentId($studentId)
    {
        return Cache::remember(self::CERTIFICATES_BY_STUDENT_CACHE_KEY . $studentId, 3600, function () use ($studentId) {
            return $this->repository->getCertificatesByStudentId($studentId);
        });
    }

    /**
     * Mengambil sertifikat berdasarkan ID program.
     *
     * @param int $programId
     * @return mixed
     */
    public function getCertificatesByProgramId($programId)
    {
        return Cache::remember(self::CERTIFICATES_BY_PROGRAM_CACHE_KEY . $programId, 3600, function () use ($programId) {
            return $this->repository->getCertificatesByProgramId($programId);
        });
    }

    /**
     * Membuat sertifikat baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createCertificate(array $data)
    {
        $result = $this->repository->createCertificate($data);
        $this->clearCache();
        return $result;
    }

    /**
     * Memperbarui sertifikat berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateCertificate($id, array $data)
    {
        $result = $this->repository->updateCertificate($id, $data);
        $this->clearCache();
        return $result;
    }

    /**
     * Menghapus sertifikat berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteCertificate($id)
    {
        $result = $this->repository->deleteCertificate($id);
        $this->clearCache();
        return $result;
    }

    /**
     * Membersihkan cache terkait sertifikat.
     */
    private function clearCache()
    {
        Cache::forget(self::ALL_CERTIFICATES_CACHE_KEY);
        // Catatan: cache berdasarkan ID, student, dan program masih ada, 
        // tapi akan diperbarui saat diminta lagi
    }
}
