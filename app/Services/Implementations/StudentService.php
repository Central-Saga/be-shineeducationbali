<?php

namespace App\Services;

use App\Repositories\Contracts\StudentRepositoryInterface;
use App\Services\Contracts\StudentServiceInterface;
use Illuminate\Support\Facades\Log;

class StudentService implements StudentServiceInterface
{
    protected $studentRepository;

    /**
     * Konstruktor untuk menginisialisasi StudentRepository.
     *
     * @param StudentRepositoryInterface $studentRepository
     */
    public function __construct(StudentRepositoryInterface $studentRepository)
    {
        $this->studentRepository = $studentRepository;
    }

    /**
     * Mengambil semua data student.
     *
     * @return mixed
     */
    public function getAllStudents()
    {
        return $this->studentRepository->getAllStudents();
    }

    /**
     * Mengambil semua data student dengan status Aktif.
     *
     * @return mixed
     */
    public function getActiveStudents()
    {
        return $this->studentRepository->getActiveStudents();
    }

    /**
     * Mengambil semua data student dengan status Non Aktif.
     *
     * @return mixed
     */
    public function getInactiveStudents()
    {
        return $this->studentRepository->getInactiveStudents();
    }

    /**
     * Mengambil data student berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getStudentById($id)
    {
        $student = $this->studentRepository->getStudentById($id);
        if (!$student) {
            throw new \Exception("Student with ID {$id} not found.");
        }
        return $student;
    }

    /**
     * Mengambil data student berdasarkan nama user.
     *
     * @param string $name
     * @return mixed
     */
    public function getStudentByName($name)
    {
        if (empty($name)) {
            throw new \Exception("Name cannot be empty.");
        }
        $student = $this->studentRepository->getStudentByName($name);
        if (!$student) {
            throw new \Exception("Student with name {$name} not found.");
        }
        return $student;
    }

    /**
     * Membuat data student baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createStudent(array $data)
    {
        $student = $this->studentRepository->createStudent($data);
        if (!$student) {
            throw new \Exception("Failed to create student.");
        }
        return $student;
    }

    /**
     * Memperbarui data student berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateStudent($id, array $data)
    {
        $student = $this->studentRepository->updateStudent($id, $data);
        if (!$student) {
            throw new \Exception("Failed to update student with ID {$id}.");
        }
        return $student;
    }

    /**
     * Menghapus data student berdasarkan ID.
     *
     * @param int $id
     * @return bool
     */
    public function deleteStudent($id)
    {
        $result = $this->studentRepository->deleteStudent($id);
        if (!$result) {
            throw new \Exception("Failed to delete student with ID {$id}.");
        }
        return $result;
    }
}
