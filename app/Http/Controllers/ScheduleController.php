<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ScheduleResource;
use App\Http\Requests\ScheduleStoreRequest;
use App\Http\Requests\ScheduleUpdateRequest;
use App\Services\Contracts\ScheduleServiceInterface;

class ScheduleController extends Controller
{
    protected $scheduleService;

    public function __construct(ScheduleServiceInterface $scheduleService)
    {
        $this->scheduleService = $scheduleService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $schedules = $this->scheduleService->getAllSchedules();
            return ScheduleResource::collection($schedules);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ScheduleStoreRequest $request)
    {
        try {
            $schedule = $this->scheduleService->createSchedule($request->validated());
            return new ScheduleResource($schedule);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $schedule = $this->scheduleService->getScheduleById($id);
            if (!$schedule) {
                return response()->json([
                    'message' => 'Jadwal tidak ditemukan'
                ], 404);
            }
            return new ScheduleResource($schedule);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ScheduleUpdateRequest $request, string $id)
    {
        try {
            $schedule = $this->scheduleService->updateSchedule($id, $request->validated());
            if (!$schedule) {
                return response()->json([
                    'message' => 'Jadwal tidak ditemukan'
                ], 404);
            }
            return new ScheduleResource($schedule);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $result = $this->scheduleService->deleteSchedule($id);
            if (!$result) {
                return response()->json([
                    'message' => 'Jadwal tidak ditemukan'
                ], 404);
            }
            return response()->json([
                'message' => 'Jadwal berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
