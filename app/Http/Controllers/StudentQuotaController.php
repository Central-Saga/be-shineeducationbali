<?php

namespace App\Http\Controllers;

use App\Models\StudentQuota;
use App\Models\Student;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class StudentQuotaController extends Controller
{
    /**
     * Display a listing of student quotas.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = StudentQuota::with(['student', 'program']);

        // Filter by student_id if provided
        if ($request->has('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        // Filter by program_id if provided
        if ($request->has('program_id')) {
            $query->where('program_id', $request->program_id);
        }

        // Filter by period if provided
        if ($request->has('period')) {
            $query->forPeriod($request->period);
        }

        // Filter quotas with remaining sessions
        if ($request->has('with_remaining') && $request->with_remaining) {
            $query->withRemainingQuota();
        }

        // Sort by a specific field
        $sortBy = $request->get('sort_by', 'period');
        $sortDirection = $request->get('sort_direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);

        // Paginate if required, otherwise get all
        $perPage = $request->get('per_page', 15);
        $quotas = $request->has('paginate') && $request->paginate ? 
                   $query->paginate($perPage) : 
                   $query->get();

        return response()->json([
            'status' => 'success',
            'data' => $quotas,
            'message' => 'Student quotas retrieved successfully'
        ]);
    }

    /**
     * Store a newly created student quota in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,id',
            'program_id' => 'required|exists:programs,id',
            'period' => 'required|date',
            'sessions_paid' => 'required|integer|min:0',
            'sessions_used' => 'sometimes|integer|min:0',
            'sessions_accumulated' => 'sometimes|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
                'message' => 'Validation failed'
            ], 422);
        }

        // Check if quota for this student and program in this period already exists
        $existingQuota = StudentQuota::where('student_id', $request->student_id)
            ->where('program_id', $request->program_id)
            ->whereDate('period', Carbon::parse($request->period)->toDateString())
            ->first();

        if ($existingQuota) {
            return response()->json([
                'status' => 'error',
                'message' => 'Quota already exists for this student and program in the specified period'
            ], 422);
        }

        // Create quota data
        $quotaData = [
            'student_id' => $request->student_id,
            'program_id' => $request->program_id,
            'period' => $request->period,
            'sessions_paid' => $request->sessions_paid,
            'sessions_used' => $request->sessions_used ?? 0,
            'sessions_accumulated' => $request->sessions_accumulated ?? 0,
        ];

        // Create StudentQuota instance
        $quota = new StudentQuota($quotaData);
        
        // Calculate remaining sessions
        $quota->calculateRemaining();
        
        // Save to database
        $quota->save();

        return response()->json([
            'status' => 'success',
            'data' => $quota->load(['student', 'program']),
            'message' => 'Student quota created successfully'
        ], 201);
    }

    /**
     * Display the specified student quota.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $quota = StudentQuota::with(['student', 'program'])->find($id);

        if (!$quota) {
            return response()->json([
                'status' => 'error',
                'message' => 'Student quota not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $quota,
            'message' => 'Student quota retrieved successfully'
        ]);
    }

    /**
     * Update the specified student quota in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $quota = StudentQuota::find($id);

        if (!$quota) {
            return response()->json([
                'status' => 'error',
                'message' => 'Student quota not found'
            ], 404);
        }

        // Validate request data
        $validator = Validator::make($request->all(), [
            'student_id' => 'sometimes|exists:students,id',
            'program_id' => 'sometimes|exists:programs,id',
            'period' => 'sometimes|date',
            'sessions_paid' => 'sometimes|integer|min:0',
            'sessions_used' => 'sometimes|integer|min:0',
            'sessions_accumulated' => 'sometimes|integer|min:0',
            'sessions_remaining' => 'sometimes|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
                'message' => 'Validation failed'
            ], 422);
        }

        // Update quota record with validated data
        $quota->update($request->only([
            'student_id',
            'program_id',
            'period',
            'sessions_paid',
            'sessions_used',
            'sessions_accumulated',
            'sessions_remaining',
        ]));

        // Recalculate remaining sessions if any session-related field was updated
        if ($request->has('sessions_paid') || $request->has('sessions_used') || $request->has('sessions_accumulated')) {
            $quota->calculateRemaining();
            $quota->save();
        }

        return response()->json([
            'status' => 'success',
            'data' => $quota->load(['student', 'program']),
            'message' => 'Student quota updated successfully'
        ]);
    }

    /**
     * Remove the specified student quota from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $quota = StudentQuota::find($id);

        if (!$quota) {
            return response()->json([
                'status' => 'error',
                'message' => 'Student quota not found'
            ], 404);
        }

        $quota->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Student quota deleted successfully'
        ]);
    }

    /**
     * Use session(s) from student quota.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function useSession(Request $request, $id)
    {
        $quota = StudentQuota::find($id);

        if (!$quota) {
            return response()->json([
                'status' => 'error',
                'message' => 'Student quota not found'
            ], 404);
        }

        // Validate request data
        $validator = Validator::make($request->all(), [
            'sessions_count' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
                'message' => 'Validation failed'
            ], 422);
        }

        // Check if there are enough sessions remaining
        $sessionsCount = $request->sessions_count;
        if ($quota->sessions_remaining < $sessionsCount) {
            return response()->json([
                'status' => 'error',
                'message' => 'Not enough remaining sessions in this quota. Available: ' . $quota->sessions_remaining,
                'available' => $quota->sessions_remaining
            ], 422);
        }

        // Use the sessions
        $quota->useSession($sessionsCount);
        $quota->save();

        return response()->json([
            'status' => 'success',
            'data' => [
                'quota' => $quota->refresh()->load(['student', 'program']),
                'sessions_used' => $sessionsCount,
                'sessions_remaining' => $quota->sessions_remaining
            ],
            'message' => $sessionsCount . ' session(s) used successfully'
        ]);
    }

    /**
     * Add paid sessions to student quota.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function addPaidSessions(Request $request, $id)
    {
        $quota = StudentQuota::find($id);

        if (!$quota) {
            return response()->json([
                'status' => 'error',
                'message' => 'Student quota not found'
            ], 404);
        }

        // Validate request data
        $validator = Validator::make($request->all(), [
            'sessions_count' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
                'message' => 'Validation failed'
            ], 422);
        }

        // Add paid sessions
        $sessionsCount = $request->sessions_count;
        $quota->addPaidSessions($sessionsCount);
        $quota->save();

        return response()->json([
            'status' => 'success',
            'data' => [
                'quota' => $quota->refresh()->load(['student', 'program']),
                'sessions_added' => $sessionsCount,
                'sessions_remaining' => $quota->sessions_remaining
            ],
            'message' => $sessionsCount . ' paid session(s) added successfully'
        ]);
    }

    /**
     * Add accumulated (bonus) sessions to student quota.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function addAccumulatedSessions(Request $request, $id)
    {
        $quota = StudentQuota::find($id);

        if (!$quota) {
            return response()->json([
                'status' => 'error',
                'message' => 'Student quota not found'
            ], 404);
        }

        // Validate request data
        $validator = Validator::make($request->all(), [
            'sessions_count' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
                'message' => 'Validation failed'
            ], 422);
        }

        // Add accumulated sessions
        $sessionsCount = $request->sessions_count;
        $quota->addAccumulatedSessions($sessionsCount);
        $quota->save();

        return response()->json([
            'status' => 'success',
            'data' => [
                'quota' => $quota->refresh()->load(['student', 'program']),
                'sessions_added' => $sessionsCount,
                'sessions_remaining' => $quota->sessions_remaining
            ],
            'message' => $sessionsCount . ' accumulated session(s) added successfully'
        ]);
    }

    /**
     * Get student quota statistics.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $studentId
     * @return \Illuminate\Http\JsonResponse
     */
    public function studentStats(Request $request, $studentId)
    {
        $student = Student::find($studentId);

        if (!$student) {
            return response()->json([
                'status' => 'error',
                'message' => 'Student not found'
            ], 404);
        }

        // Default to current year if not provided
        $year = $request->get('year', Carbon::now()->year);
        
        // Get all quotas for this student in the specified year
        $quotas = StudentQuota::where('student_id', $studentId)
            ->whereYear('period', $year)
            ->with('program')
            ->get();

        // Group quotas by program
        $programStats = [];
        $programIds = $quotas->pluck('program_id')->unique();
        
        foreach ($programIds as $programId) {
            $programQuotas = $quotas->where('program_id', $programId);
            $program = $programQuotas->first()->program;
            
            $programStats[] = [
                'program' => $program,
                'total_sessions_paid' => $programQuotas->sum('sessions_paid'),
                'total_sessions_used' => $programQuotas->sum('sessions_used'),
                'total_sessions_accumulated' => $programQuotas->sum('sessions_accumulated'),
                'total_sessions_remaining' => $programQuotas->sum('sessions_remaining')
            ];
        }

        // Calculate overall stats
        $overallStats = [
            'total_programs' => $programIds->count(),
            'total_sessions_paid' => $quotas->sum('sessions_paid'),
            'total_sessions_used' => $quotas->sum('sessions_used'),
            'total_sessions_accumulated' => $quotas->sum('sessions_accumulated'),
            'total_sessions_remaining' => $quotas->sum('sessions_remaining'),
            'usage_rate' => $quotas->sum('sessions_paid') > 0 ? 
                round(($quotas->sum('sessions_used') / ($quotas->sum('sessions_paid') + $quotas->sum('sessions_accumulated'))) * 100, 2) . '%' : 
                '0%'
        ];

        return response()->json([
            'status' => 'success',
            'data' => [
                'student' => $student,
                'year' => $year,
                'overall_stats' => $overallStats,
                'program_stats' => $programStats
            ],
            'message' => 'Student quota statistics retrieved successfully'
        ]);
    }

    /**
     * Get program quota statistics.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $programId
     * @return \Illuminate\Http\JsonResponse
     */
    public function programStats(Request $request, $programId)
    {
        $program = Program::find($programId);

        if (!$program) {
            return response()->json([
                'status' => 'error',
                'message' => 'Program not found'
            ], 404);
        }

        // Default to current year if not provided
        $year = $request->get('year', Carbon::now()->year);
        
        // Get all quotas for this program in the specified year
        $quotas = StudentQuota::where('program_id', $programId)
            ->whereYear('period', $year)
            ->with('student')
            ->get();

        // Calculate statistics
        $totalStudents = $quotas->pluck('student_id')->unique()->count();
        $totalSessionsPaid = $quotas->sum('sessions_paid');
        $totalSessionsUsed = $quotas->sum('sessions_used');
        $totalSessionsAccumulated = $quotas->sum('sessions_accumulated');
        $totalSessionsRemaining = $quotas->sum('sessions_remaining');
        
        // Calculate usage rate
        $totalAvailableSessions = $totalSessionsPaid + $totalSessionsAccumulated;
        $usageRate = $totalAvailableSessions > 0 ? 
            round(($totalSessionsUsed / $totalAvailableSessions) * 100, 2) : 0;

        // Get monthly breakdown
        $monthlyStats = [];
        for ($month = 1; $month <= 12; $month++) {
            $monthQuotas = $quotas->filter(function($quota) use ($month) {
                return Carbon::parse($quota->period)->month === $month;
            });
            
            $monthlyStats[] = [
                'month' => Carbon::createFromDate($year, $month, 1)->format('F'),
                'month_number' => $month,
                'students_count' => $monthQuotas->pluck('student_id')->unique()->count(),
                'sessions_paid' => $monthQuotas->sum('sessions_paid'),
                'sessions_used' => $monthQuotas->sum('sessions_used'),
                'sessions_accumulated' => $monthQuotas->sum('sessions_accumulated'),
                'sessions_remaining' => $monthQuotas->sum('sessions_remaining')
            ];
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'program' => $program,
                'year' => $year,
                'statistics' => [
                    'total_students' => $totalStudents,
                    'total_sessions_paid' => $totalSessionsPaid,
                    'total_sessions_used' => $totalSessionsUsed,
                    'total_sessions_accumulated' => $totalSessionsAccumulated,
                    'total_sessions_remaining' => $totalSessionsRemaining,
                    'usage_rate' => $usageRate . '%'
                ],
                'monthly_stats' => $monthlyStats
            ],
            'message' => 'Program quota statistics retrieved successfully'
        ]);
    }
}
