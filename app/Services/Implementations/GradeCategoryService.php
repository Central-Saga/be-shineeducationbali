<?php

namespace App\Services;

use App\Repositories\Interfaces\GradeCategoryRepositoryInterface;
use App\Services\Interfaces\GradeCategoryServiceInterface;
use App\Models\GradeCategory;
use Illuminate\Database\Eloquent\Collection;

class GradeCategoryService implements GradeCategoryServiceInterface
{
    protected $repository;

    public function __construct(GradeCategoryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAllGradeCategories(): Collection
    {
        return $this->repository->all();
    }

    public function getGradeCategoryById(int $id): GradeCategory
    {
        return $this->repository->find($id);
    }

    public function createGradeCategory(array $data): GradeCategory
    {
        // Validasi atau logika bisnis tambahan dapat ditambahkan di sini
        return $this->repository->create($data);
    }

    public function updateGradeCategory(int $id, array $data): GradeCategory
    {
        // Validasi atau logika bisnis tambahan dapat ditambahkan di sini
        return $this->repository->update($id, $data);
    }

    public function deleteGradeCategory(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
