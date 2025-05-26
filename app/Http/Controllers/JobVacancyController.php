<?php

namespace App\Http\Controllers;

use App\Http\Requests\JobVacancyStoreRequest;
use App\Http\Requests\JobVacancyUpdateRequest;
use App\Http\Resources\JobVacancyResource;
use App\Services\Contracts\JobVacancyServiceInterface;
use Illuminate\Http\Request;

class JobVacancyController extends Controller
{
    protected $jobVacancyService;

    public function __construct(JobVacancyServiceInterface $jobVacancyService)
    {
        $this->jobVacancyService = $jobVacancyService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $status = $request->query('status');

        if ($status === null) {
            $jobVacancies = $this->jobVacancyService->getAll();
        } elseif ($status === '1') {
            $jobVacancies = $this->jobVacancyService->getJobVacanciesByStatusOpen();
        } elseif ($status === '0') {
            $jobVacancies = $this->jobVacancyService->getJobVacanciesByStatusClosed();
        } else {
            return response()->json([
                'error' => 'Invalid status parameter',
                'message' => 'Use 1 for active (open) or 0 for inactive (closed) job vacancies'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Job vacancies retrieved successfully',
            'data' => JobVacancyResource::collection($jobVacancies)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(JobVacancyStoreRequest $request)
    {
        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'salary' => 'required|numeric|min:0',
            'application_deadline' => 'required|date|after_or_equal:today',
            'status' => 'required|in:0,1',
        ]);

        // Convert numeric status to Open/Closed
        $validated['status'] = $validated['status'] === '1' ? 'Open' : 'Closed';

        $jobVacancy = $this->jobVacancyService->create($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Job vacancy created successfully',
            'data' => new JobVacancyResource($jobVacancy)
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $jobVacancy = $this->jobVacancyService->getById($id);
        return new JobVacancyResource($jobVacancy);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(JobVacancyUpdateRequest $request, $id)
    {
        $data = $request->validated();
        $jobVacancy = $this->jobVacancyService->update($id, $data);
        return new JobVacancyResource($jobVacancy);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->jobVacancyService->delete($id);
        return response()->json(['message' => 'Job vacancy deleted successfully'], 200);
    }
}
