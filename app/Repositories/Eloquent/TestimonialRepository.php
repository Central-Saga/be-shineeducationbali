<?php

namespace App\Repositories;

use App\Models\Testimonial;
use App\Repositories\Contracts\TestimonialRepositoryInterface;

class TestimonialRepository implements TestimonialRepositoryInterface
{
    /**
     * Mengambil semua data testimonial beserta relasi student.
     *
     * @return mixed
     */
    public function getAllTestimonials()
    {
        return Testimonial::with('student')->latest()->get();
    }

    /**
     * Mengambil data testimonial berdasarkan ID beserta relasi student.
     *
     * @param int $id
     * @return mixed
     */
    public function getTestimonialById($id)
    {
        return Testimonial::with('student')->findOrFail($id);
    }

    /**
     * Mengambil data testimonial berdasarkan nama student (berdasarkan relasi dengan student).
     *
     * @param string $name
     * @return mixed
     */
    public function getTestimonialByName($name)
    {
        return Testimonial::with('student')
            ->whereHas('student.user', function ($query) use ($name) {
                $query->where('name', 'like', '%' . $name . '%');
            })
            ->get();
    }

    /**
     * Membuat data testimonial baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createTestimonial(array $data)
    {
        return Testimonial::create($data);
    }

    /**
     * Memperbarui data testimonial berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateTestimonial($id, array $data)
    {
        $testimonial = Testimonial::findOrFail($id);
        $testimonial->update($data);
        return $testimonial;
    }

    /**
     * Menghapus data testimonial berdasarkan ID.
     *
     * @param int $id
     * @return bool
     */
    public function deleteTestimonial($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        return $testimonial->delete();
    }
}
