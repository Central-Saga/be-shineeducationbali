<?php

namespace App\Repositories\Eloquent;

use App\Models\ClassRoom;
use Illuminate\Support\Facades\Log;
use App\Repositories\Contracts\ClassRoomRepositoryInterface;

class ClassRoomRepository implements ClassRoomRepositoryInterface
{
    protected $model;

    public function __construct(ClassRoom $model)
    {
        $this->model = $model;
    }

    /**
     * Mengambil semua class rooms.
     *
     * @return mixed
     */
    public function getAllClassRooms()
    {
        return $this->model->with('program', 'teacher', 'schedules', 'students', 'assignments')->get();
    }

    /**
     * Mengambil class room berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getClassRoomById($id)
    {
        try {
            // Mengambil permission berdasarkan ID, handle jika tidak ditemukan
            return $this->model->with('program', 'teacher', 'schedules', 'students', 'assignments')->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("Class room with ID {$id} not found.");
            return null;
        }
    }

    /**
     * Mengambil class room berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getClassRoomByName($name)
    {
        return $this->model->with('program', 'teacher', 'schedules', 'students', 'assignments')->where('name', $name)->first();
    }

    /**
     * Membuat class room baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createClassRoom(array $data)
    {
        try {
            $classRoom = $this->model->create($data);
            return $classRoom->load('program', 'teacher', 'schedules', 'students', 'assignments');
        } catch (\Exception $e) {
            Log::error("Failed to create class room: {$e->getMessage()}");
            return null;
        }
    }

    /**
     * Memperbarui class room berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateClassRoom($id, array $data)
    {
        $classRoom = $this->findClassRoom($id);

        if ($classRoom) {
            try {
                $classRoom->update($data);
                return $classRoom->load('program', 'teacher', 'schedules', 'students', 'assignments');
            } catch (\Exception $e) {
                Log::error("Failed to update class room with ID {$id}: {$e->getMessage()}");
                return null;
            }
        }
        return null;
    }

    /**
     * Menghapus class room berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteClassRoom($id)
    {
        $classRoom = $this->findClassRoom($id);

        if ($classRoom) {
            try {
                $classRoom->delete();
                return true;
            } catch (\Exception $e) {
                Log::error("Failed to delete class room with ID {$id}: {$e->getMessage()}");
                return false;
            }
        }
        return false;
    }

    /**
     * Helper method untuk menemukan class room berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    protected function findClassRoom($id)
    {
        try {
            return $this->model->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("Class room with ID {$id} not found.");
            return null;
        }
    }
}
