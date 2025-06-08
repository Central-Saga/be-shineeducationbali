<?php

namespace App\Services\Implementations;

use App\Services\Contracts\JobApplicationServiceInterface;
use App\Repositories\Contracts\JobApplicationRepositoryInterface;
use App\Models\JobApplicationStatus;

class JobApplicationService implements JobApplicationServiceInterface
{
    protected $repository;

    public function __construct(JobApplicationRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get all job applications.
     *
     * @return mixed
     */
    public function getAll()
    {
        return $this->repository->getAll();
    }

    /**
     * Get a job application by ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getById($id)
    {
        return $this->repository->getById($id);
    }

    /**
     * Get job applications by name (user's name).
     *
     * @param string $name
     * @return mixed
     */
    public function getByName($name)
    {
        return $this->repository->getByName($name);
    }

    /**
     * Get job applications by status.
     *
     * @param string $status
     * @return mixed
     */
    public function getByStatus($status)
    {
        // Validasi status
        if (!in_array($status, [
            JobApplicationStatus::Pending->value,
            JobApplicationStatus::Reviewed->value,
            JobApplicationStatus::Accepted->value,
            JobApplicationStatus::Rejected->value
        ])) {
            throw new \InvalidArgumentException("Invalid status value: $status");
        }

        return $this->repository->getByStatus($status);
    }

    /**
     * Get job applications with status 'Pending'.
     *
     * @return mixed
     */
    public function getJobApplicationsByStatusPending()
    {
        return $this->repository->getByStatus(JobApplicationStatus::Pending->value);
    }

    /**
     * Get job applications with status 'Reviewed'.
     *
     * @return mixed
     */
    public function getJobApplicationsByStatusReviewed()
    {
        return $this->repository->getByStatus(JobApplicationStatus::Reviewed->value);
    }

    /**
     * Get job applications with status 'Accepted'.
     *
     * @return mixed
     */
    public function getJobApplicationsByStatusAccepted()
    {
        \Log::info('Fetching applications with status Accepted: ' . JobApplicationStatus::Accepted->value);
        $result = $this->repository->getByStatus(JobApplicationStatus::Accepted->value);
        \Log::info('Found ' . $result->count() . ' accepted applications');
        return $result;
    }

    /**
     * Get job applications with status 'Rejected'.
     *
     * @return mixed
     */
    public function getJobApplicationsByStatusRejected()
    {
        \Log::info('Fetching applications with status Rejected: ' . JobApplicationStatus::Rejected->value);
        $result = $this->repository->getByStatus(JobApplicationStatus::Rejected->value);
        \Log::info('Found ' . $result->count() . ' rejected applications');
        return $result;
    }

    /**
     * Create a new job application.
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return $this->repository->create($data);
    }

    /**
     * Update an existing job application.
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
     * Delete a job application.
     *
     * @param int $id
     * @return mixed
     */
    public function delete($id)
    {
        return $this->repository->delete($id);
    }
}
