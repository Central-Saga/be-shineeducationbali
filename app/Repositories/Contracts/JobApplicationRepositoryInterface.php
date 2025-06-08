<?php

namespace App\Repositories\Contracts;

use App\Models\JobApplication;
use Illuminate\Database\Eloquent\Collection;

interface JobApplicationRepositoryInterface
{
    /**
     * Get all job applications.
     *
     * @return Collection
     */
    public function getAll(): Collection;

    /**
     * Get a job application by ID.
     *
     * @param int $id
     * @return JobApplication|null
     */
    public function getById($id): ?JobApplication;

    /**
     * Get job applications by name.
     *
     * @param string $name
     * @return Collection
     */
    public function getByName($name): Collection;

    /**
     * Get job applications by status.
     *
     * @param string $status
     * @return Collection
     */
    public function getByStatus($status): Collection;

    /**
     * Create a new job application.
     *
     * @param array $data
     * @return JobApplication
     */
    public function create(array $data): JobApplication;

    /**
     * Update an existing job application.
     *
     * @param int $id
     * @param array $data
     * @return JobApplication|null
     */
    public function update($id, array $data): ?JobApplication;

    /**
     * Delete a job application.
     *
     * @param int $id
     * @return bool
     */
    public function delete($id): bool;
}
