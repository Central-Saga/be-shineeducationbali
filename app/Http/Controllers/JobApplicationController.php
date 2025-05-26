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
        } elseif ($status === '0') {
            $jobApplications = $this->jobApplicationService->getJobApplicationsByStatusPending();
        } elseif ($status === '1') {
            $jobApplications = $this->jobApplicationService->getJobApplicationsByStatusReviewed();
        } elseif ($status === '2') {
            $jobApplications = $this->jobApplicationService->getJobApplicationsByStatusAccepted();
        } elseif ($status === '3') {
            $jobApplications = $this->jobApplicationService->getJobApplicationsByStatusRejected();
        } else {
            return response()->json([
                'error' => 'Invalid status parameter',
                'message' => 'Use: 0 for pending, 1 for reviewed, 2 for accepted, 3 for rejected'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Job applications retrieved successfully',
            'data' => JobApplicationResource::collection($jobApplications)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(JobApplicationStoreRequest $request)
    {
        $validated = $request->validate([
            'vacancy_id' => 'required|exists:job_vacancies,id',
            'user_id' => 'required|exists:users,id',
            'application_date' => 'required|date',
            'status' => 'required|in:0,1,2,3',
        ]);

        // Convert numeric status to string status
        $statusMap = [
            '0' => 'Pending',
            '1' => 'Reviewed',
            '2' => 'Accepted',
            '3' => 'Rejected'
        ];

        $validated['status'] = $statusMap[$validated['status']];

        $jobApplication = $this->jobApplicationService->create($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Job application created successfully',
            'data' => new JobApplicationResource($jobApplication)
        ]);
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
