<?php

namespace App\Http\Controllers;

use App\Http\Requests\JobApplicationStoreRequest;
use App\Http\Requests\JobApplicationUpdateRequest;
use App\Http\Resources\JobApplicationResource;
use App\Services\Contracts\JobApplicationServiceInterface;
use Illuminate\Http\Request;

class JobApplicationController extends Controller
{
    protected $jobApplicationService;

    public function __construct(JobApplicationServiceInterface $jobApplicationService)
    {
        $this->jobApplicationService = $jobApplicationService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $status = $request->query('status');

        if ($status === null) {
            $jobApplications = $this->jobApplicationService->getAll();
        } elseif (strtolower($status) === 'pending') {
            $jobApplications = $this->jobApplicationService->getJobApplicationsByStatusPending();
        } elseif (strtolower($status) === 'reviewed') {
            $jobApplications = $this->jobApplicationService->getJobApplicationsByStatusReviewed();
        } elseif (strtolower($status) === 'accepted') {
            $jobApplications = $this->jobApplicationService->getJobApplicationsByStatusAccepted();
        } elseif (strtolower($status) === 'rejected') {
            $jobApplications = $this->jobApplicationService->getJobApplicationsByStatusRejected();
        } else {
            return response()->json(['error' => 'Invalid status parameter'], 400);
        }

        return JobApplicationResource::collection($jobApplications);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(JobApplicationStoreRequest $request)
    {
        $data = $request->validated();
        $jobApplication = $this->jobApplicationService->create($data);
        return new JobApplicationResource($jobApplication);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $jobApplication = $this->jobApplicationService->getById($id);
        return new JobApplicationResource($jobApplication);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(JobApplicationUpdateRequest $request, $id)
    {
        $data = $request->validated();
        $jobApplication = $this->jobApplicationService->update($id, $data);
        return new JobApplicationResource($jobApplication);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->jobApplicationService->delete($id);
        return response()->json(['message' => 'Job application deleted successfully'], 200);
    }
}
