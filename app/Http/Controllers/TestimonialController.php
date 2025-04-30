<?php

namespace App\Http\Controllers;

use App\Http\Requests\TestimonialStoreRequest;
use App\Http\Requests\TestimonialUpdateRequest;
use App\Http\Resources\TestimonialResource;
use App\Services\Contracts\TestimonialServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class TestimonialController extends Controller
{
    /**
     * TestimonialService instance.
     *
     * @var TestimonialServiceInterface
     */
    protected $testimonialService;

    /**
     * Constructor.
     *
     * @param TestimonialServiceInterface $testimonialService
     */
    public function __construct(TestimonialServiceInterface $testimonialService)
    {
        $this->testimonialService = $testimonialService;
    }

    /**
     * Display a listing of the testimonials.
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        $testimonials = $this->testimonialService->getAllTestimonials();
        return TestimonialResource::collection($testimonials);
    }

    /**
     * Store a newly created testimonial in storage.
     *
     * @param TestimonialStoreRequest $request
     * @return TestimonialResource
     */
    public function store(TestimonialStoreRequest $request): TestimonialResource
    {
        $testimonial = $this->testimonialService->createTestimonial($request->validated());
        return new TestimonialResource($testimonial);
    }

    /**
     * Display the specified testimonial.
     *
     * @param int $id
     * @return TestimonialResource
     */
    public function show(int $id): TestimonialResource
    {
        $testimonial = $this->testimonialService->getTestimonialById($id);
        return new TestimonialResource($testimonial);
    }

    /**
     * Update the specified testimonial in storage.
     *
     * @param TestimonialUpdateRequest $request
     * @param int $id
     * @return TestimonialResource
     */
    public function update(TestimonialUpdateRequest $request, int $id): TestimonialResource
    {
        $testimonial = $this->testimonialService->updateTestimonial($id, $request->validated());
        return new TestimonialResource($testimonial);
    }

    /**
     * Remove the specified testimonial from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy(int $id): Response
    {
        $this->testimonialService->deleteTestimonial($id);
        return response()->noContent();
    }

    /**
     * Display testimonials by student name.
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function getByName(Request $request): AnonymousResourceCollection
    {
        $request->validate([
            'name' => 'required|string|min:2',
        ]);

        $testimonials = $this->testimonialService->getTestimonialByName($request->name);
        return TestimonialResource::collection($testimonials);
    }
}
