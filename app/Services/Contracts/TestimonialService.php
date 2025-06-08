<?php

namespace App\Services\Contracts;

use App\Models\Testimonial;
use Illuminate\Database\Eloquent\Collection;

interface TestimonialService
{
    /**
     * Get all testimonials
     *
     * @return Collection
     */
    public function getAll(): Collection;

    /**
     * Get testimonial by ID
     *
     * @param int $id
     * @return Testimonial|null
     */
    public function getById(int $id): ?Testimonial;

    /**
     * Create new testimonial
     *
     * @param array $data
     * @return Testimonial
     */
    public function create(array $data): Testimonial;

    /**
     * Update testimonial
     *
     * @param int $id
     * @param array $data
     * @return Testimonial
     */
    public function update(int $id, array $data): Testimonial;

    /**
     * Delete testimonial
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * Get testimonials by name
     *
     * @param string $name
     * @return Collection
     */
    public function getByName(string $name): Collection;
}
