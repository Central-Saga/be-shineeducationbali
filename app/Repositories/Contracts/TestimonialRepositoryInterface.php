<?php

namespace App\Repositories\Contracts;

interface TestimonialRepositoryInterface
{
    /**
     * Mengambil semua data testimonial beserta relasi student.
     *
     * @return mixed
     */
    public function getAllTestimonials();

    /**
     * Mengambil data testimonial berdasarkan ID beserta relasi student.
     *
     * @param int $id
     * @return mixed
     */
    public function getTestimonialById($id);

    /**
     * Mengambil data testimonial berdasarkan nama student (berdasarkan relasi dengan student).
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
     * Memperbarui data testimonial berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateTestimonial($id, array $data);

    /**
     * Menghapus data testimonial berdasarkan ID.
     *
     * @param int $id
     * @return bool
     */
    public function deleteTestimonial($id);
}
