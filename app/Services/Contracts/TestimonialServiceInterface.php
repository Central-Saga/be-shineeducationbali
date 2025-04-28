<?php

namespace App\Services\Contracts;

interface TestimonialServiceInterface
{
    /**
     * Mengambil semua data testimonial.
     *
     * @return mixed
     */
    public function getAllTestimonials();

    /**
     * Mengambil data testimonial berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getTestimonialById($id);

    /**
     * Mengambil data testimonial berdasarkan nama student.
     *
     * @param string $name
     * @return mixed
     */
    public function getTestimonialByName($name);

    /**
     * Membuat data testimonial baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createTestimonial(array $data);

    /**
     * Memperbarui data testimonial.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateTestimonial($id, array $data);

    /**
     * Menghapus data testimonial.
     *
     * @param int $id
     * @return bool
     */
    public function deleteTestimonial($id);
}
