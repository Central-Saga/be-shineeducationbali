<?php

namespace App\Services\Contracts;

interface CertificateGradeServiceInterface
{
    /**
     * Mengambil semua nilai sertifikat.
     *
     * @return mixed
     */
    public function getAllCertificateGrades();

    /**
     * Mengambil nilai sertifikat berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getCertificateGradeById($id);

    /**
     * Mengambil nilai sertifikat berdasarkan ID sertifikat.
     *
     * @param int $certificateId
     * @return mixed
     */
    public function getCertificateGradesByCertificateId($certificateId);

    /**
     * Mengambil nilai sertifikat berdasarkan ID nilai (grade).
     *
     * @param int $gradeId
     * @return mixed
     */
    public function getCertificateGradesByGradeId($gradeId);

    /**
     * Membuat nilai sertifikat baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createCertificateGrade(array $data);

    /**
     * Memperbarui nilai sertifikat berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateCertificateGrade($id, array $data);

    /**
     * Menghapus nilai sertifikat berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteCertificateGrade($id);
}
