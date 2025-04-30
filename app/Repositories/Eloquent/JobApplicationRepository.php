<?php

namespace App\Repositories\Eloquent;

use App\Models\JobApplication;
use App\Models\JobApplicationStatus;
use App\Repositories\Contracts\JobApplicationRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

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
     * @return Collection
     */
    public function getAll(): Collection
    {
        return $this->model->with(['jobVacancy', 'user'])->get();
    }

    /**
     * Get a job application by ID.
     *
     * @param int $id
     * @return JobApplication|null
     */
    public function getById($id): ?JobApplication
    {
        return $this->model->find($id)?->load(['jobVacancy', 'user']);
    }

    /**
     * Get job applications by name (user's name).
     *
     * @param string $name
     * @return Collection
     */
    public function getByName($name): Collection
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
     * @return Collection
     */
    public function getByStatus($status): Collection
    {
        return $this->model->with(['jobVacancy', 'user'])
            ->where('status', $status)
            ->get();
    }

    /**
     * Create a new job application.
     *
     * @param array $data
     * @return JobApplication
     */
    public function create(array $data): JobApplication
    {
        return $this->model->create($data);
    }

    /**
     * Update an existing job application.
     *
     * @param int $id
     * @param array $data
     * @return JobApplication|null
     */
    public function update($id, array $data): ?JobApplication
    {
        $jobApplication = $this->getById($id);
        if (!$jobApplication) {
            return null;
        }
        
        $jobApplication->update($data);
        return $jobApplication;
    }

    /**
     * Delete a job application.
     *
     * @param int $id
     * @return bool
     */
    public function delete($id): bool
    {
        $jobApplication = $this->getById($id);
        if (!$jobApplication) {
            return false;
        }
        
        return $jobApplication->delete();
    }
}
