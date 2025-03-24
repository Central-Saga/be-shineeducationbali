<?php

namespace App\Services\Implementations;

use App\Services\Contracts\ClassRoomServiceInterface;
use App\Repositories\Contracts\ClassRoomRepositoryInterface;
use App\Services\Contracts\StudentServiceInterface;
use Illuminate\Support\Facades\Cache;

class ClassRoomService implements ClassRoomServiceInterface
{
    protected $repository;
    protected $studentService;

    const CLASS_ROOMS_ALL_CACHE_KEY = 'class_rooms.all';

    public function __construct(
        ClassRoomRepositoryInterface $repository,
        StudentServiceInterface $studentService
    ) {
        $this->repository = $repository;
        $this->studentService = $studentService;
    }
    /**
     * Mengambil semua class rooms.
     *
     * @return mixed
     */
    public function getAllClassRooms()
    {
        return Cache::remember(self::CLASS_ROOMS_ALL_CACHE_KEY, 3600, function () {
            return $this->repository->getAllClassRooms();
        });
    }

    /**
     * Mengambil class room berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getClassRoomById($id)
    {
        return $this->repository->getClassRoomById($id);
    }

    /**
     * Mengambil class room berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getClassRoomByName($name)
    {
        return $this->repository->getClassRoomByName($name);
    }

    /**
     * Membuat class room baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createClassRoom(array $data)
    {
        $result = $this->repository->createClassRoom($data);
        $this->clearClassRoomCaches();
        return $result;
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
        $result = $this->repository->updateClassRoom($id, $data);
        $this->clearClassRoomCaches();
        return $result;
    }

    /**
     * Menghapus class room berdasarkan ID.
     *
     * @param int $id
     * @return bool
     */
    public function deleteClassRoom($id)
    {
        $result = $this->repository->deleteClassRoom($id);
        $this->clearClassRoomCaches();

        return $result;
    }

    /**
     * Menghapus semua cache class room
     *
     * @return void
     */
    public function clearClassRoomCaches()
    {
        Cache::forget(self::CLASS_ROOMS_ALL_CACHE_KEY);
    }

    /**
     * Menambahkan siswa ke dalam classroom
     *
     * @param int $classRoomId
     * @param int $studentId
     * @return mixed
     */
    public function attachStudentToClassRoom($classRoomId, $studentId)
    {
        try {
            // 1. Validasi classroom
            $classRoom = $this->repository->getClassRoomById($classRoomId);
            if (!$classRoom) {
                throw new \Exception('Classroom not found');
            }

            // 2. Validasi student
            $student = $this->studentService->getStudentById($studentId);
            if (!$student) {
                throw new \Exception('Student not found');
            }

            // 3. Cek kapasitas classroom
            $currentStudentsCount = $classRoom->students()->count();
            if ($currentStudentsCount >= $classRoom->capacity) {
                throw new \Exception('Classroom capacity is full');
            }

            // 4. Cek apakah siswa sudah ada di classroom
            if ($classRoom->students()->where('student_id', $studentId)->exists()) {
                throw new \Exception('Student is already in this classroom');
            }

            // 5. Tambahkan siswa ke classroom
            $classRoom->students()->attach($studentId);

            // 6. Clear cache
            $this->clearClassRoomCaches();

            return $classRoom;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Menghapus siswa dari classroom
     *
     * @param int $classRoomId
     * @param int $studentId
     * @return mixed
     */
    public function detachStudentFromClassRoom($classRoomId, $studentId)
    {
        try {
            // 1. Validasi classroom
            $classRoom = $this->repository->getClassRoomById($classRoomId);
            if (!$classRoom) {
                throw new \Exception('Classroom not found');
            }

            // 2. Hapus relasi
            $classRoom->students()->detach($studentId);

            // 3. Clear cache
            $this->clearClassRoomCaches();

            return $classRoom;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Mengambil daftar siswa dalam classroom
     *
     * @param int $classRoomId
     * @return mixed
     */
    public function getStudentsInClassRoom($classRoomId)
    {
        try {
            // 1. Validasi classroom
            $classRoom = $this->repository->getClassRoomById($classRoomId);
            if (!$classRoom) {
                throw new \Exception('Classroom not found');
            }

            // 2. Return siswa dengan data lengkap
            return $classRoom->students()->with(['user', 'program'])->get();
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
