<?php

namespace App\Services\Implementations;

use App\Services\Contracts\JobVacancyServiceInterface;
use App\Repositories\Contracts\JobVacancyRepositoryInterface;
use App\Models\JobVacancyStatus;

class JobVacancyService implements JobVacancyServiceInterface
{
    protected $repository;

    public function __construct(JobVacancyRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get all job vacancies.
     *
     * @return mixed
     */
    public function getAll()
    {
        return $this->repository->getAll();
    }

    /**
     * Get a job vacancy by ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getById($id)
    {
        return $this->repository->getById($id);
    }

    /**
     * Get job vacancies by name (title).
     *
     * @param string $name
     * @return mixed
     */
    public function getByName($name)
    {
        return $this->repository->getByName($name);
    }

    /**
     * Get job vacancies by status.
     *
     * @param string $status
     * @return mixed
     */
    public function getByStatus($status)
    {
        // Validasi status
        if (!in_array($status, [JobVacancyStatus::Open->value, JobVacancyStatus::Closed->value])) {
            throw new \InvalidArgumentException("Invalid status value: $status");
        }

        return $this->repository->getByStatus($status);
    }

    /**
     * Get job vacancies with status 'Open'.
     *
     * @return mixed
     */
    public function getJobVacanciesByStatusOpen()
    {
        return $this->repository->getByStatus(JobVacancyStatus::Open->value);
    }

    /**
     * Get job vacancies with status 'Closed'.
     *
     * @return mixed
     */
    public function getJobVacanciesByStatusClosed()
    {
        return $this->repository->getByStatus(JobVacancyStatus::Closed->value);
    }

    /**
     * Create a new job vacancy.
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return $this->repository->create($data);
    }

    /**
     * Update an existing job vacancy.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function update($id, array $data)
    {
        return $this->repository->update($id, $data);
    }

    /**
     * Delete a job vacancy.
     *
     * @param int $id
     * @return mixed
     */
    public function delete($id)
    {
        return $this->repository->delete($id);
    }
}
