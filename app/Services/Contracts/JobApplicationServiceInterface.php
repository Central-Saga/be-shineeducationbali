<?php

namespace App\Services;

interface JobApplicationServiceInterface
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
     * Get job applications by name (user's name).
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
     * Get job applications with status 'Pending'.
     *
     * @return mixed
     */
    public function getJobApplicationsByStatusPending();

    /**
     * Get job applications with status 'Reviewed'.
     *
     * @return mixed
     */
    public function getJobApplicationsByStatusReviewed();

    /**
     * Get job applications with status 'Accepted'.
     *
     * @return mixed
     */
    public function getJobApplicationsByStatusAccepted();

    /**
     * Get job applications with status 'Rejected'.
     *
     * @return mixed
     */
    public function getJobApplicationsByStatusRejected();

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
