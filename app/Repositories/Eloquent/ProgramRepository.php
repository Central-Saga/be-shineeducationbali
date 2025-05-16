<?php

namespace App\Repositories\Eloquent;

use App\Models\Program;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Repositories\Contracts\ProgramRepositoryInterface;

class ProgramRepository implements ProgramRepositoryInterface
{
    protected $model;

    public function __construct(Program $model)
    {
        $this->model = $model;
    }

    /**
     * Mengambil semua program.
     *
     * @return mixed
     */
    public function getAllPrograms()
    {
        return $this->model->with('educationLevel', 'subject', 'classType', 'meetingFrequency')->get();
    }

    /**
     * Mengambil program berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getProgramById($id)
    {
        try {
            // Mengambil permission berdasarkan ID, handle jika tidak ditemukan
            return $this->model->with('educationLevel', 'subject', 'classType', 'meetingFrequency')->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("Program with ID {$id} not found.");
            return null;
        }
    }

    /**
     * Mengambil program berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getProgramByName($name)
    {
        return $this->model->where('name', $name)->with('educationLevel', 'subject', 'classType', 'meetingFrequency')->first();
    }

    /**
     * Mengambil program berdasarkan status.
     *
     * @param string $status
     * @return mixed
     */
    public function getProgramsByStatus($status)
    {
        return $this->model->where('status', $status)
                          ->with('educationLevel', 'subject', 'classType', 'meetingFrequency')
                          ->get();
    }

    /**
     * Membuat program baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createProgram(array $data)
    {
        try {
            $program = $this->model->create($data);
            $program->load('educationLevel', 'subject', 'classType', 'meetingFrequency');
            return $program;
        } catch (\Exception $e) {
            Log::error("Failed to create program: {$e->getMessage()}");
            return null;
        }
    }

    /**
     * Memperbarui program berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateProgram($id, array $data)
    {
        $program = $this->findProgram($id);

        if ($program) {
            try {
                $program->update($data);
                $program->load('educationLevel', 'subject', 'classType', 'meetingFrequency');
                return $program;
            } catch (\Exception $e) {
                Log::error("Failed to update program with ID {$id}: {$e->getMessage()}");
                return null;
            }
        }
        return null;
    }

    /**
     * Menghapus program berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteProgram($id)
    {
        $program = $this->findProgram($id);

        if ($program) {
            try {
                $program->delete();
                return true;
            } catch (\Exception $e) {
                Log::error("Failed to delete program with ID {$id}: {$e->getMessage()}");
                return false;
            }
        }
        return false;
    }

    /**
     * Helper method untuk menemukan program berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    protected function findProgram($id)
    {
        try {
            return $this->model->with('educationLevel', 'subject', 'classType', 'meetingFrequency')->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("Program with ID {$id} not found.");
            return null;
        }
    }
}
