<?php

namespace App\Repositories;

interface JobApplicationRepositoryInterface
{
    /**
     * Get all job applications.
     *
     * @return mixed
     */
    public function getAll();

    /**
     * Get a job application by ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getById($id);

    /**
     * Get job applications by name.
     *
     * @param string $name
     * @return mixed
     */
    public function getByName($name);

    /**
     * Get job applications by status.
     *
     * @param string $status
     * @return mixed
     */
    public function getByStatus($status);

    /**
     * Create a new job application.
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data);

    /**
     * Update an existing job application.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function update($id, array $data);

    /**
     * Delete a job application.
     *
     * @param int $id
     * @return mixed
     */
    public function delete($id);
}
