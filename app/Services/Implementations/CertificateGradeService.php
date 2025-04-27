<?php

namespace App\Services\Implementations;

use App\Services\Contracts\CertificateGradeServiceInterface;
use App\Repositories\Contracts\CertificateGradeRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class CertificateGradeService implements CertificateGradeServiceInterface
{
    protected $repository;

    // Mendefinisikan key untuk caching
    const CERTIFICATE_GRADES_ALL_CACHE_KEY = 'certificate_grades_all';
    const CERTIFICATE_GRADES_BY_CERTIFICATE_CACHE_KEY = 'certificate_grades_by_certificate';
    const CERTIFICATE_GRADES_BY_GRADE_CACHE_KEY = 'certificate_grades_by_grade';

    public function __construct(CertificateGradeRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Mengambil semua nilai sertifikat.
     *
     * @return mixed
     */
    public function getAllCertificateGrades()
    {
        return Cache::remember(self::CERTIFICATE_GRADES_ALL_CACHE_KEY, 3600, function () {
            return $this->repository->getAllCertificateGrades();
        });
    }

    /**
     * Mengambil nilai sertifikat berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getCertificateGradeById($id)
    {
        return $this->repository->getCertificateGradeById($id);
    }

    /**
     * Mengambil nilai sertifikat berdasarkan ID sertifikat.
     *
     * @param int $certificateId
     * @return mixed
     */
    public function getCertificateGradesByCertificateId($certificateId)
    {
        $cacheKey = self::CERTIFICATE_GRADES_BY_CERTIFICATE_CACHE_KEY . '_' . $certificateId;
        return Cache::remember($cacheKey, 3600, function () use ($certificateId) {
            return $this->repository->getCertificateGradesByCertificateId($certificateId);
        });
    }

    /**
     * Mengambil nilai sertifikat berdasarkan ID nilai (grade).
     *
     * @param int $gradeId
     * @return mixed
     */
    public function getCertificateGradesByGradeId($gradeId)
    {
        $cacheKey = self::CERTIFICATE_GRADES_BY_GRADE_CACHE_KEY . '_' . $gradeId;
        return Cache::remember($cacheKey, 3600, function () use ($gradeId) {
            return $this->repository->getCertificateGradesByGradeId($gradeId);
        });
    }

    /**
     * Membuat nilai sertifikat baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createCertificateGrade(array $data)
    {
        $result = $this->repository->createCertificateGrade($data);
        $this->clearCertificateGradeCaches();
        return $result;
    }

    /**
     * Memperbarui nilai sertifikat berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateCertificateGrade($id, array $data)
    {
        $result = $this->repository->updateCertificateGrade($id, $data);
        $this->clearCertificateGradeCaches();
        return $result;
    }

    /**
     * Menghapus nilai sertifikat berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteCertificateGrade($id)
    {
        $result = $this->repository->deleteCertificateGrade($id);
        $this->clearCertificateGradeCaches();
        return $result;
    }

    /**
     * Menghapus semua cache nilai sertifikat
     *
     * @return void
     */
    public function clearCertificateGradeCaches()
    {
        Cache::forget(self::CERTIFICATE_GRADES_ALL_CACHE_KEY);
        
        // Untuk cache yang lebih spesifik, kita bisa menambahkan logic tambahan
        // untuk menghapus cache dengan ID tertentu
        // Contoh: Cache::forget(self::CERTIFICATE_GRADES_BY_CERTIFICATE_CACHE_KEY . '_' . $certificateId);
    }
}
