<?php

namespace App\Repositories;

use App\Models\JobVacancy;
use App\Models\JobVacancyStatus;

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
     * @return mixed
     */
    public function getAll()
    {
        return $this->model->with('subject')->get();
    }

    /**
     * Get a job vacancy by ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getById($id)
    {
        return $this->model->with('subject')->findOrFail($id);
    }

    /**
     * Get job vacancies by name (title).
     *
     * @param string $name
     * @return mixed
     */
    public function getByName($name)
    {
        return $this->model->with('subject')
            ->where('title', 'like', '%' . $name . '%')
            ->get();
    }

    /**
     * Get job vacancies by status.
     *
     * @param string $status
     * @return mixed
     */
    public function getByStatus($status)
    {
        return $this->model->with('subject')
            ->where('status', $status)
            ->get();
    }

    /**
     * Create a new job vacancy.
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return $this->model->create($data);
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
        $jobVacancy = $this->model->findOrFail($id);
        $jobVacancy->update($data);
        return $jobVacancy;
    }

    /**
     * Delete a job vacancy.
     *
     * @param int $id
     * @return mixed
     */
    public function delete($id)
    {
        $jobVacancy = $this->model->findOrFail($id);
        return $jobVacancy->delete();
    }
}
