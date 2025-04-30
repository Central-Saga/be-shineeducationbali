<?php

namespace App\Http\Controllers;

use App\Http\Requests\JobVacancyStoreRequest;
use App\Http\Requests\JobVacancyUpdateRequest;
use App\Http\Resources\JobVacancyResource;
use App\Services\JobVacancyServiceInterface;
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
        } elseif (strtolower($status) === 'open') {
            $jobVacancies = $this->jobVacancyService->getJobVacanciesByStatusOpen();
        } elseif (strtolower($status) === 'closed') {
            $jobVacancies = $this->jobVacancyService->getJobVacanciesByStatusClosed();
        } else {
            return response()->json(['error' => 'Invalid status parameter'], 400);
        }

        return JobVacancyResource::collection($jobVacancies);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(JobVacancyStoreRequest $request)
    {
        $data = $request->validated();
        $jobVacancy = $this->jobVacancyService->create($data);
        return new JobVacancyResource($jobVacancy);
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
