<?php

namespace App\Services\Implementations;

use App\Repositories\Contracts\TestimonialRepositoryInterface;
use App\Services\Contracts\TestimonialServiceInterface;

class TestimonialService implements TestimonialServiceInterface
{
    /**
     * TestimonialRepository instance.
     *
     * @var TestimonialRepositoryInterface
     */
    protected $testimonialRepository;

    /**
     * Constructor.
     *
     * @param TestimonialRepositoryInterface $testimonialRepository
     */
    public function __construct(TestimonialRepositoryInterface $testimonialRepository)
    {
        $this->testimonialRepository = $testimonialRepository;
    }

    /**
     * Mengambil semua data testimonial.
     *
     * @return mixed
     */
    public function getAllTestimonials()
    {
        return $this->testimonialRepository->getAllTestimonials();
    }

    /**
     * Mengambil data testimonial berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getTestimonialById($id)
    {
        return $this->testimonialRepository->getTestimonialById($id);
    }

    /**
     * Mengambil data testimonial berdasarkan nama student.
     *
     * @param string $name
     * @return mixed
     */
    public function getTestimonialByName($name)
    {
        return $this->testimonialRepository->getTestimonialByName($name);
    }

    /**
     * Membuat data testimonial baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createTestimonial(array $data)
    {
        return $this->testimonialRepository->createTestimonial($data);
    }

    /**
     * Memperbarui data testimonial.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateTestimonial($id, array $data)
    {
        return $this->testimonialRepository->updateTestimonial($id, $data);
    }

    /**
     * Menghapus data testimonial.
     *
     * @param int $id
     * @return bool
     */
    public function deleteTestimonial($id)
    {
        return $this->testimonialRepository->deleteTestimonial($id);
    }
}
