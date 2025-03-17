<?php

namespace App\Repositories\Eloquent;

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
        return $this->model->all();
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
            return $this->model->findOrFail($id);
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
        return $this->model->where('name', $name)->first();
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
            return $this->model->create($data);
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
     * Helper method untuk menemukan frekuensi pertemuan berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    protected function findProgram($id)
    {
        try {
            return $this->model->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("Program with ID {$id} not found.");
            return null;
        }
    }
}
