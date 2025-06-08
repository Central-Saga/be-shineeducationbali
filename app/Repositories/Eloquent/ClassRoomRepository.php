<?php

namespace App\Repositories\Eloquent;

use App\Models\ClassRoom;
use Illuminate\Support\Facades\Log;
use App\Repositories\Contracts\ClassRoomRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
     * Mengambil class room berdasarkan status.
     *
     * @param string $status
     * @return mixed
     */
    public function getClassRoomByStatus($status)
    {
        return $this->model->with('program', 'teacher', 'schedules', 'students', 'assignments')->where('status', $status)->get();
    }

    /**
     * Mengambil class room yang aktif.
     *
     * @return mixed
     */
    public function getActiveClassRooms()
    {
        return $this->model->with('program', 'teacher', 'schedules', 'students', 'assignments')->where('status', 'Aktif')->get();
    }

    /**
     * Mengambil class room yang tidak aktif.
     *
     * @return mixed
     */
    public function getInactiveClassRooms()
    {
        Log::channel('daily')->info('Getting inactive classrooms from repository - start');
        try {
            // Build query
            $query = $this->model->query()
                ->with(['program', 'teacher', 'schedules', 'students', 'assignments'])
                ->where('status', '=', 'Non Aktif');
            
            // Debug query
            $queryStr = $query->toSql();
            $bindings = $query->getBindings();
            Log::channel('daily')->info('Query debug:', [
                'sql' => $queryStr,
                'bindings' => $bindings,
            ]);
            
            // Log the query
            Log::info('Inactive classrooms query:', [
                'sql' => $query->toSql(),
                'bindings' => $query->getBindings()
            ]);
            
            // Execute query
            $results = $query->get();
            
            // Log results in detail
            Log::channel('daily')->info('Inactive classrooms found:', [
                'count' => $results->count(),
                'statuses' => $results->pluck('status')->unique()->toArray(),
                'ids' => $results->pluck('id')->toArray(),
                'first_record' => $results->first() ? $results->first()->toArray() : null
            ]);
            
            return $results;
        } catch (\Exception $e) {
            Log::error('Error getting inactive classrooms:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
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
