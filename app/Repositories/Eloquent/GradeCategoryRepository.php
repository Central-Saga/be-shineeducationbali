<?php

namespace App\Repositories;

use App\Models\GradeCategory;
use App\Repositories\Interfaces\GradeCategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class GradeCategoryRepository implements GradeCategoryRepositoryInterface
{
    protected $model;

    public function __construct(GradeCategory $model)
    {
        $this->model = $model;
    }

    public function all(): Collection
    {
        return $this->model->with('program')->get();
    }

    public function find(int $id): ?GradeCategory
    {
        return $this->model->with('program')->findOrFail($id);
    }

    public function create(array $data): GradeCategory
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): GradeCategory
    {
        $gradeCategory = $this->find($id);
        $gradeCategory->update($data);
        return $gradeCategory->fresh();
    }

    public function delete(int $id): bool
    {
        $gradeCategory = $this->find($id);
        return $gradeCategory->delete();
    }
}
