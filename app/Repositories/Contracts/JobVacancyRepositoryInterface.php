<?php

namespace App\Repositories;

interface JobVacancyRepositoryInterface
{
    /**
     * Get all job vacancies.
     *
     * @return mixed
     */
    public function getAll();

    /**
     * Get a job vacancy by ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getById($id);

    /**
     * Get job vacancies by name (title).
     *
     * @param string $name
     * @return mixed
     */
    public function getByName($name);

    /**
     * Get job vacancies by status.
     *
     * @param string $status
     * @return mixed
     */
    public function getByStatus($status);

    /**
     * Create a new job vacancy.
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data);

    /**
     * Update an existing job vacancy.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function update($id, array $data);

    /**
     * Delete a job vacancy.
     *
     * @param int $id
     * @return mixed
     */
    public function delete($id);
}
