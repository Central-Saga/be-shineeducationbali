<?php

namespace App\Repositories\Eloquent;

use App\Models\CertificateGrade;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Repositories\Contracts\CertificateGradeRepositoryInterface;

class CertificateGradeRepository implements CertificateGradeRepositoryInterface
{
    protected $model;

    public function __construct(CertificateGrade $model)
    {
        $this->model = $model;
    }

    /**
     * Mengambil semua nilai sertifikat.
     *
     * @return mixed
     */
    public function getAllCertificateGrades()
    {
        return $this->model->with(['certificate', 'grade'])->get();
    }

    /**
     * Mengambil nilai sertifikat berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getCertificateGradeById($id)
    {
        try {
            return $this->model->with(['certificate', 'grade'])->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("Certificate Grade with ID {$id} not found.");
            return null;
        }
    }

    /**
     * Mengambil nilai sertifikat berdasarkan ID sertifikat.
     *
     * @param int $certificateId
     * @return mixed
     */
    public function getCertificateGradesByCertificateId($certificateId)
    {
        return $this->model->where('certificate_id', $certificateId)
            ->with(['certificate', 'grade'])
            ->get();
    }

    /**
     * Mengambil nilai sertifikat berdasarkan ID nilai (grade).
     *
     * @param int $gradeId
     * @return mixed
     */
    public function getCertificateGradesByGradeId($gradeId)
    {
        return $this->model->where('grade_id', $gradeId)
            ->with(['certificate', 'grade'])
            ->get();
    }

    /**
     * Membuat nilai sertifikat baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createCertificateGrade(array $data)
    {
        try {
            $certificateGrade = $this->model->create($data);
            $certificateGrade->load(['certificate', 'grade']);
            return $certificateGrade;
        } catch (\Exception $e) {
            Log::error("Failed to create certificate grade: {$e->getMessage()}");
            return null;
        }
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
        $certificateGrade = $this->findCertificateGrade($id);

        if ($certificateGrade) {
            try {
                $certificateGrade->update($data);
                $certificateGrade->load(['certificate', 'grade']);
                return $certificateGrade;
            } catch (\Exception $e) {
                Log::error("Failed to update certificate grade with ID {$id}: {$e->getMessage()}");
                return null;
            }
        }
        return null;
    }

    /**
     * Menghapus nilai sertifikat berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteCertificateGrade($id)
    {
        $certificateGrade = $this->findCertificateGrade($id);

        if ($certificateGrade) {
            try {
                $certificateGrade->delete();
                return true;
            } catch (\Exception $e) {
                Log::error("Failed to delete certificate grade with ID {$id}: {$e->getMessage()}");
                return false;
            }
        }
        return false;
    }

    /**
     * Helper method untuk menemukan nilai sertifikat berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    protected function findCertificateGrade($id)
    {
        try {
            return $this->model->with(['certificate', 'grade'])->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("Certificate Grade with ID {$id} not found.");
            return null;
        }
    }
}
