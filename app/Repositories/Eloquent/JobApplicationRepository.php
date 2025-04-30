<?php

namespace App\Repositories;

use App\Models\JobApplication;
use App\Models\JobApplicationStatus;

class JobApplicationRepository implements JobApplicationRepositoryInterface
{
    protected $model;

    public function __construct(JobApplication $model)
    {
        $this->model = $model;
    }

    /**
     * Get all job applications.
     *
     * @return mixed
     */
    public function getAll()
    {
        return $this->model->with(['jobVacancy', 'user'])->get();
    }

    /**
     * Get a job application by ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getById($id)
    {
        return $this->model->with(['jobVacancy', 'user'])->findOrFail($id);
    }

    /**
     * Get job applications by name (user's name).
     *
     * @param string $name
     * @return mixed
     */
    public function getByName($name)
    {
        return $this->model->with(['jobVacancy', 'user'])
            ->whereHas('user', function ($query) use ($name) {
                $query->where('name', 'like', '%' . $name . '%');
            })
            ->get();
    }

    /**
     * Get job applications by status.
     *
     * @param string $status
     * @return mixed
     */
    public function getByStatus($status)
    {
        return $this->model->with(['jobVacancy', 'user'])
            ->where('status', $status)
            ->get();
    }

    /**
     * Create a new job application.
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return $this->model->create($data);
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
        $jobApplication = $this->model->findOrFail($id);
        $jobApplication->update($data);
        return $jobApplication;
    }

    /**
     * Delete a job application.
     *
     * @param int $id
     * @return mixed
     */
    public function delete($id)
    {
        $jobApplication = $this->model->findOrFail($id);
        return $jobApplication->delete();
    }
}
