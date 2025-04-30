<?php

namespace App\Repositories\Contracts;

use App\Models\JobVacancy;
use Illuminate\Database\Eloquent\Collection;

interface JobVacancyRepositoryInterface
{
    /**
     * Get all job vacancies.
     *
     * @return Collection
     */
    public function getAll(): Collection;

    /**
     * Get a job vacancy by ID.
     *
     * @param int $id
     * @return JobVacancy|null
     */
    public function getById($id): ?JobVacancy;

    /**
     * Get job vacancies by name (title).
     *
     * @param string $name
     * @return Collection
     */
    public function getByName($name): Collection;

    /**
     * Get job vacancies by status.
     *
     * @param string $status
     * @return Collection
     */
    public function getByStatus($status): Collection;

    /**
     * Create a new job vacancy.
     *
     * @param array $data
     * @return JobVacancy
     */
    public function create(array $data): JobVacancy;

    /**
     * Update an existing job vacancy.
     *
     * @param int $id
     * @param array $data
     * @return JobVacancy|null
     */
    public function update($id, array $data): ?JobVacancy;

    /**
     * Delete a job vacancy.
     *
     * @param int $id
     * @return bool
     */
    public function delete($id): bool;
}
