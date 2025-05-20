<?php

namespace App\Http\Controllers;

use App\Models\TeacherAttendance;
use App\Models\Teacher;
use App\Models\ClassRoom;
use App\Http\Resources\TeacherAttendanceResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class TeacherAttendanceController extends Controller
{
    /**
     * Display a listing of the teacher attendances.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = TeacherAttendance::with(['teacher', 'classRoom']);

        // Filter by teacher_id if provided
        if ($request->has('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }

        // Filter by class_rooms_id if provided
        if ($request->has('class_rooms_id')) {
            $query->where('class_rooms_id', $request->class_rooms_id);
        }

        // Filter by date if provided
        if ($request->has('date')) {
            $query->forDate($request->date);
        }

        // Filter by status if provided (accept 0/1 or string)
        if ($request->has('status')) {
            $status = $request->status;
            if ($status === '1' || $status === 1) {
                $status = TeacherAttendance::STATUS_PRESENT;
            } elseif ($status === '0' || $status === 0) {
                $status = TeacherAttendance::STATUS_ABSENT;
            }
            $query->withStatus($status);
        }

        // Filter by present status
        if ($request->has('present') && $request->present) {
            $query->present();
        }

        // Filter by absent status
        if ($request->has('absent') && $request->absent) {
            $query->absent();
        }

        // Sort by a specific field
        $sortBy = $request->get('sort_by', 'attendance_date');
        $sortDirection = $request->get('sort_direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);

        // Paginate if required, otherwise get all
        $perPage = $request->get('per_page', 15);
        $attendances = $request->has('paginate') && $request->paginate ? 
                        $query->paginate($perPage) : 
                        $query->get();

        // Log jika data kosong
        if ($attendances->isEmpty()) {
            \Log::info('TeacherAttendanceController@index: Data kosong', [
                'filters' => $request->all()
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => TeacherAttendanceResource::collection($attendances),
            'message' => 'Teacher attendances retrieved successfully'
        ]);
    }

    /**
     * Store a newly created teacher attendance in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'teacher_id' => 'required|exists:teachers,id',
            'class_rooms_id' => 'required|exists:class_rooms,id',
            'attendance_date' => 'required|date',
            'check_in' => 'required|date_format:Y-m-d H:i:s',
            'check_out' => 'nullable|date_format:Y-m-d H:i:s|after:check_in',
            'status' => 'required|in:' . TeacherAttendance::STATUS_PRESENT . ',' . TeacherAttendance::STATUS_ABSENT,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
                'message' => 'Validation failed'
            ], 422);
        }

        // Check if attendance already exists for this teacher on this date
        $existingAttendance = TeacherAttendance::where('teacher_id', $request->teacher_id)
            ->where('class_rooms_id', $request->class_rooms_id)
            ->whereDate('attendance_date', Carbon::parse($request->attendance_date)->toDateString())
            ->first();

        if ($existingAttendance) {
            return response()->json([
                'status' => 'error',
                'message' => 'Attendance already recorded for this teacher, class, and date'
            ], 422);
        }

        // Create new attendance record
        $attendance = TeacherAttendance::create([
            'teacher_id' => $request->teacher_id,
            'class_rooms_id' => $request->class_rooms_id,
            'attendance_date' => $request->attendance_date,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'status' => $request->status,
        ]);

        return response()->json([
            'status' => 'success',
            'data' => new TeacherAttendanceResource($attendance->load(['teacher', 'classRoom'])),
            'message' => 'Teacher attendance recorded successfully'
        ], 201);
    }

    /**
     * Display the specified teacher attendance.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $attendance = TeacherAttendance::with(['teacher', 'classRoom'])->find($id);

        if (!$attendance) {
            return response()->json([
                'status' => 'error',
                'message' => 'Teacher attendance not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => new TeacherAttendanceResource($attendance),
            'message' => 'Teacher attendance retrieved successfully'
        ]);
    }

    /**
     * Update the specified teacher attendance in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $attendance = TeacherAttendance::find($id);

        if (!$attendance) {
            return response()->json([
                'status' => 'error',
                'message' => 'Teacher attendance not found'
            ], 404);
        }

        // Validate request data
        $validator = Validator::make($request->all(), [
            'teacher_id' => 'sometimes|exists:teachers,id',
            'class_rooms_id' => 'sometimes|exists:class_rooms,id',
            'attendance_date' => 'sometimes|date',
            'check_in' => 'sometimes|date_format:Y-m-d H:i:s',
            'check_out' => 'nullable|date_format:Y-m-d H:i:s|after:check_in',
            'status' => 'sometimes|in:' . TeacherAttendance::STATUS_PRESENT . ',' . TeacherAttendance::STATUS_ABSENT,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
                'message' => 'Validation failed'
            ], 422);
        }

        // Update attendance record with validated data
        $attendance->update($request->only([
            'teacher_id',
            'class_rooms_id',
            'attendance_date',
            'check_in',
            'check_out',
            'status',
        ]));

        return response()->json([
            'status' => 'success',
            'data' => new TeacherAttendanceResource($attendance->load(['teacher', 'classRoom'])),
            'message' => 'Teacher attendance updated successfully'
        ]);
    }

    /**
     * Remove the specified teacher attendance from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $attendance = TeacherAttendance::find($id);

        if (!$attendance) {
            return response()->json([
                'status' => 'error',
                'message' => 'Teacher attendance not found'
            ], 404);
        }

        $attendance->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Teacher attendance deleted successfully'
        ]);
    }

    /**
     * Check in a teacher.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkIn(Request $request)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'teacher_id' => 'required|exists:teachers,id',
            'class_rooms_id' => 'required|exists:class_rooms,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
                'message' => 'Validation failed'
            ], 422);
        }

        $now = Carbon::now();
        $today = $now->toDateString();

        // Check if attendance already exists for today
        $attendance = TeacherAttendance::where('teacher_id', $request->teacher_id)
            ->where('class_rooms_id', $request->class_rooms_id)
            ->whereDate('attendance_date', $today)
            ->first();

        if ($attendance) {
            return response()->json([
                'status' => 'error',
                'message' => 'Teacher has already checked in for this class today'
            ], 422);
        }

        // Create new attendance record with check-in time
        $attendance = TeacherAttendance::create([
            'teacher_id' => $request->teacher_id,
            'class_rooms_id' => $request->class_rooms_id,
            'attendance_date' => $today,
            'check_in' => $now,
            'status' => TeacherAttendance::STATUS_PRESENT,
        ]);

        return response()->json([
            'status' => 'success',
            'data' => new TeacherAttendanceResource($attendance->load(['teacher', 'classRoom'])),
            'message' => 'Teacher checked in successfully'
        ], 201);
    }

    /**
     * Check out a teacher.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkOut(Request $request)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'teacher_id' => 'required|exists:teachers,id',
            'class_rooms_id' => 'required|exists:class_rooms,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
                'message' => 'Validation failed'
            ], 422);
        }

        $now = Carbon::now();
        $today = $now->toDateString();

        // Find today's attendance record for this teacher and class
        $attendance = TeacherAttendance::where('teacher_id', $request->teacher_id)
            ->where('class_rooms_id', $request->class_rooms_id)
            ->whereDate('attendance_date', $today)
            ->first();

        if (!$attendance) {
            return response()->json([
                'status' => 'error',
                'message' => 'No check-in record found for today'
            ], 404);
        }

        if ($attendance->check_out) {
            return response()->json([
                'status' => 'error',
                'message' => 'Teacher has already checked out for this class today'
            ], 422);
        }

        // Update attendance with check-out time
        $attendance->update([
            'check_out' => $now
        ]);

        // Calculate duration
        $durationInMinutes = $attendance->getDurationInMinutes();

        return response()->json([
            'status' => 'success',
            'data' => new TeacherAttendanceResource($attendance->load(['teacher', 'classRoom'])),
            'message' => 'Teacher checked out successfully'
        ]);
    }

    /**
     * Get attendance statistics for a teacher.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $teacherId
     * @return \Illuminate\Http\JsonResponse
     */
    public function teacherStats(Request $request, $teacherId)
    {
        $teacher = Teacher::find($teacherId);

        if (!$teacher) {
            return response()->json([
                'status' => 'error',
                'message' => 'Teacher not found'
            ], 404);
        }

        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->toDateString());

        $attendances = TeacherAttendance::where('teacher_id', $teacherId)
            ->whereBetween('attendance_date', [$startDate, $endDate])
            ->with(['teacher', 'classRoom'])
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => TeacherAttendanceResource::collection($attendances),
            'message' => 'Teacher attendance statistics retrieved successfully'
        ]);
    }

    /**
     * Get attendance statistics for a class room.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $classRoomId
     * @return \Illuminate\Http\JsonResponse
     */
    public function classRoomStats(Request $request, $classRoomId)
    {
        $classRoom = ClassRoom::find($classRoomId);

        if (!$classRoom) {
            return response()->json([
                'status' => 'error',
                'message' => 'Class room not found'
            ], 404);
        }

        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->toDateString());

        $attendances = TeacherAttendance::where('class_rooms_id', $classRoomId)
            ->whereBetween('attendance_date', [$startDate, $endDate])
            ->with(['teacher', 'classRoom'])
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => TeacherAttendanceResource::collection($attendances),
            'message' => 'Class room attendance statistics retrieved successfully'
        ]);
    }
}
