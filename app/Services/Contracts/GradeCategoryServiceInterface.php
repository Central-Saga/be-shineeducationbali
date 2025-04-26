<?php

namespace App\Services\Interfaces;

use App\Models\GradeCategory;
use Illuminate\Database\Eloquent\Collection;

interface GradeCategoryServiceInterface
{
    /**
     * Retrieve all grade categories.
     *
     * @return Collection
     */
    public function getAllGradeCategories(): Collection;

    /**
     * Find a grade category by its ID.
     *
     * @param int $id
     * @return GradeCategory
     */
    public function getGradeCategoryById(int $id): GradeCategory;

    /**
     * Create a new grade category.
     *
     * @param array $data
     * @return GradeCategory
     */
    public function createGradeCategory(array $data): GradeCategory;

    /**
     * Update an existing grade category.
     *
     * @param int $id
     * @param array $data
     * @return GradeCategory
     */
    public function updateGradeCategory(int $id, array $data): GradeCategory;

    /**
     * Delete a grade category by its ID.
     *
     * @param int $id
     * @return bool
     */
    public function deleteGradeCategory(int $id): bool;
}
