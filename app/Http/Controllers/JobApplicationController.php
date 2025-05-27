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
        \Log::info('Received status parameter: ' . ($status ?? 'null'));

        // Validasi status hanya boleh null, '0', '1', '2', atau '3'
        if (!is_null($status) && !in_array($status, ['0', '1', '2', '3'])) {
            return response()->json([
                'error' => 'Invalid status parameter',
                'message' => 'Use: 1 for pending, 2 for reviewed, 3 for accepted, 0 for rejected'
            ], 400);
        }

        if ($status === null) {
            $jobApplications = $this->jobApplicationService->getAll();
        } elseif ($status === '1') {
            \Log::info('Getting pending applications');
            $jobApplications = $this->jobApplicationService->getByStatus('Pending');
        } elseif ($status === '2') {
            \Log::info('Getting reviewed applications');
            $jobApplications = $this->jobApplicationService->getByStatus('Reviewed');
        } elseif ($status === '3') {
            \Log::info('Getting accepted applications');
            $jobApplications = $this->jobApplicationService->getByStatus('Accepted');
        } elseif ($status === '0') {
            \Log::info('Getting rejected applications');
            $jobApplications = $this->jobApplicationService->getByStatus('Rejected');
        }

        \Log::info('Found applications count: ' . $jobApplications->count());
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
            '1' => 'Pending',
            '2' => 'Reviewed',
            '3' => 'Accepted',
            '0' => 'Rejected',
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
     * Update the specified resource in storage.
     */
    public function update(JobApplicationUpdateRequest $request, $id)
    {
        $validated = $request->validate([
            'vacancy_id' => 'sometimes|exists:job_vacancies,id',
            'user_id' => 'sometimes|exists:users,id',
            'application_date' => 'sometimes|date',
            'status' => 'sometimes|in:0,1,2,3',
        ]);

        // Convert numeric status to string status if status is being updated
        if (isset($validated['status'])) {
            $statusMap = [
                '1' => 'Pending',
                '2' => 'Reviewed',
                '3' => 'Accepted',
                '0' => 'Rejected',
            ];
            $validated['status'] = $statusMap[$validated['status']];
        }

        $jobApplication = $this->jobApplicationService->update($id, $validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Job application updated successfully',
            'data' => new JobApplicationResource($jobApplication)
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $jobApplication = $this->jobApplicationService->getById($id);
        if (!$jobApplication) {
            return response()->json([
                'success' => false,
                'message' => 'Job application not found'
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => new JobApplicationResource($jobApplication)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $success = $this->jobApplicationService->delete($id);
        return response()->json([
            'success' => $success,
            'message' => $success ? 'Job application deleted successfully' : 'Job application not found'
        ]);
    }
}
