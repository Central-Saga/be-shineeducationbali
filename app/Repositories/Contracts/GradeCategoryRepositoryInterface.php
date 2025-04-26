<?php

namespace App\Repositories\Interfaces;

use App\Models\GradeCategory;
use Illuminate\Database\Eloquent\Collection;

interface GradeCategoryRepositoryInterface
{
    /**
     * Retrieve all grade categories.
     *
     * @return Collection
     */
    public function all(): Collection;

    /**
     * Find a grade category by its ID.
     *
     * @param int $id
     * @return GradeCategory|null
     */
    public function find(int $id): ?GradeCategory;

    /**
     * Create a new grade category.
     *
     * @param array $data
     * @return GradeCategory
     */
    public function create(array $data): GradeCategory;

    /**
     * Update an existing grade category.
     *
     * @param int $id
     * @param array $data
     * @return GradeCategory
     */
    public function update(int $id, array $data): GradeCategory;

    /**
     * Delete a grade category by its ID.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;
}
