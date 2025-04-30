<?php

namespace App\Repositories\Eloquent;

use App\Models\JobVacancy;
use App\Models\JobVacancyStatus;
use App\Repositories\Contracts\JobVacancyRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class JobVacancyRepository implements JobVacancyRepositoryInterface
{
    protected $model;

    public function __construct(JobVacancy $model)
    {
        $this->model = $model;
    }

    /**
     * Get all job vacancies.
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return $this->model->with('subject')->get();
    }

    /**
     * Get a job vacancy by ID.
     *
     * @param int $id
     * @return JobVacancy|null
     */
    public function getById($id): ?JobVacancy
    {
        return $this->model->find($id)?->load('subject');
    }

    /**
     * Get job vacancies by name (title).
     *
     * @param string $name
     * @return Collection
     */
    public function getByName($name): Collection
    {
        return $this->model->with('subject')
            ->where('title', 'like', '%' . $name . '%')
            ->get();
    }

    /**
     * Get job vacancies by status.
     *
     * @param string $status
     * @return Collection
     */
    public function getByStatus($status): Collection
    {
        return $this->model->with('subject')
            ->where('status', $status)
            ->get();
    }

    /**
     * Create a new job vacancy.
     *
     * @param array $data
     * @return JobVacancy
     */
    public function create(array $data): JobVacancy
    {
        return $this->model->create($data);
    }

    /**
     * Update an existing job vacancy.
     *
     * @param int $id
     * @param array $data
     * @return JobVacancy|null
     */
    public function update($id, array $data): ?JobVacancy
    {
        $jobVacancy = $this->getById($id);
        if (!$jobVacancy) {
            return null;
        }
        
        $jobVacancy->update($data);
        return $jobVacancy;
    }

    /**
     * Delete a job vacancy.
     *
     * @param int $id
     * @return bool
     */
    public function delete($id): bool
    {
        $jobVacancy = $this->getById($id);
        if (!$jobVacancy) {
            return false;
        }
        
        return $jobVacancy->delete();
    }
}
