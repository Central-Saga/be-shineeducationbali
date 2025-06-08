<?php

namespace App\Services\Contracts;

interface CertificateServiceInterface
{
    /**
     * Mengambil semua sertifikat.
     *
     * @return mixed
     */
    public function getAllCertificates();

    /**
     * Mengambil sertifikat berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getCertificateById($id);

    /**
     * Mengambil sertifikat berdasarkan ID siswa.
     *
     * @param int $studentId
     * @return mixed
     */
    public function getCertificatesByStudentId($studentId);

    /**
     * Mengambil sertifikat berdasarkan ID program.
     *
     * @param int $programId
     * @return mixed
     */
    public function getCertificatesByProgramId($programId);

    /**
     * Membuat sertifikat baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createCertificate(array $data);

    /**
     * Memperbarui sertifikat berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateCertificate($id, array $data);

    /**
     * Menghapus sertifikat berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteCertificate($id);
}
