<?php

namespace App\Repositories\Eloquent;

use App\Models\Certificate;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Repositories\Contracts\CertificateRepositoryInterface;

class CertificateRepository implements CertificateRepositoryInterface
{
    protected $model;

    public function __construct(Certificate $model)
    {
        $this->model = $model;
    }

    /**
     * Mengambil semua sertifikat.
     *
     * @return mixed
     */
    public function getAllCertificates()
    {
        return $this->model->with(['student', 'program'])->get();
    }

    /**
     * Mengambil sertifikat berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getCertificateById($id)
    {
        try {
            return $this->model->with(['student', 'program'])->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("Certificate with ID {$id} not found.");
            return null;
        }
    }

    /**
     * Mengambil sertifikat berdasarkan ID siswa.
     *
     * @param int $studentId
     * @return mixed
     */
    public function getCertificatesByStudentId($studentId)
    {
        return $this->model->where('student_id', $studentId)
            ->with(['student', 'program'])
            ->get();
    }

    /**
     * Mengambil sertifikat berdasarkan ID program.
     *
     * @param int $programId
     * @return mixed
     */
    public function getCertificatesByProgramId($programId)
    {
        return $this->model->where('program_id', $programId)
            ->with(['student', 'program'])
            ->get();
    }

    /**
     * Membuat sertifikat baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createCertificate(array $data)
    {
        try {
            $certificate = $this->model->create($data);
            $certificate->load(['student', 'program']);
            return $certificate;
        } catch (\Exception $e) {
            Log::error("Failed to create certificate: {$e->getMessage()}");
            return null;
        }
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
        try {
            $certificate = $this->getCertificateById($id);
            if ($certificate) {
                $certificate->update($data);
                $certificate->load(['student', 'program']);
                return $certificate;
            }
            return null;
        } catch (\Exception $e) {
            Log::error("Failed to update certificate with ID {$id}: {$e->getMessage()}");
            return null;
        }
    }

    /**
     * Menghapus sertifikat berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteCertificate($id)
    {
        try {
            $certificate = $this->getCertificateById($id);
            if ($certificate) {
                $certificate->delete();
                return true;
            }
            return false;
        } catch (\Exception $e) {
            Log::error("Failed to delete certificate with ID {$id}: {$e->getMessage()}");
            return false;
        }
    }
}
