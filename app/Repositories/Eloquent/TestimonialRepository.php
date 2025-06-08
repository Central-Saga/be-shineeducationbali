<?php

namespace App\Repositories\Eloquent;

use App\Models\Testimonial;
use App\Repositories\Contracts\TestimonialRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class TestimonialRepository implements TestimonialRepositoryInterface
{
    protected $model;

    /**
     * TestimonialRepository constructor.
     *
     * @param Testimonial $model
     */
    public function __construct(Testimonial $model)
    {
        $this->model = $model;
    }

    /**
     * Get all testimonials
     *
     * @return Collection
     */
    public function getAllTestimonials(): Collection
    {
        return $this->model->latest()->get();
    }

    /**
     * Get testimonial by id
     *
     * @param int $id
     * @return Testimonial|null
     */
    public function getTestimonialById($id): ?Testimonial
    {
        try {
            return $this->model->findOrFail($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Illuminate\Support\Facades\Log::error("Testimonial with ID {$id} not found.");
            return null;
        }
    }

    /**
     * Get testimonial by name
     *
     * @param string $name
     * @return Collection
     */
    public function getTestimonialByName($name): Collection
    {
        return $this->model->where('name', 'like', "%{$name}%")
            ->orWhere('title', 'like', "%{$name}%")
            ->latest()
            ->get();
    }

    /**
     * Get testimonials by status
     *
     * @param string $status
     * @return Collection
     */
    public function getByStatus($status): Collection
    {
        return $this->model->where('status', $status)->latest()->get();
    }

    /**
     * Create testimonial
     *
     * @param array $data
     * @return Testimonial
     */
    public function createTestimonial(array $data): Testimonial
    {
        return $this->model->create($data);
    }

    /**
     * Update testimonial
     *
     * @param int $id
     * @param array $data
     * @return Testimonial|null
     */
    public function updateTestimonial($id, array $data): ?Testimonial
    {
        $testimonial = $this->getTestimonialById($id);
        if (!$testimonial) {
            return null;
        }
        
        $testimonial->update($data);
        return $testimonial;
    }

    /**
     * Delete testimonial
     *
     * @param int $id
     * @return bool
     */
    public function deleteTestimonial($id): bool
    {
        $testimonial = $this->getTestimonialById($id);
        if (!$testimonial) {
            return false;
        }
        
        return $testimonial->delete();
    }
}
