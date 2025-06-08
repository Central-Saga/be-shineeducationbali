<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\MeetingFrequencyResource;
use App\Http\Requests\MeetingFrequencyStoreRequest;
use App\Http\Requests\MeetingFrequencyUpdateRequest;
use App\Services\Contracts\MeetingFrequencyServiceInterface;

class MeetingFrequencyController extends Controller
{
    /**
     * @var MeetingFrequencyServiceInterface $meetingFrequencyService
     */
    protected $meetingFrequencyService;

    /**
     * Konstruktor MeetingFrequencyController.
     */
    public function __construct(MeetingFrequencyServiceInterface $meetingFrequencyService)
    {
        $this->meetingFrequencyService = $meetingFrequencyService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $meetingFrequencies = $this->meetingFrequencyService->getAllMeetingFrequencies();
        return MeetingFrequencyResource::collection($meetingFrequencies);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MeetingFrequencyStoreRequest $request)
    {
        $meetingFrequency = $this->meetingFrequencyService->createMeetingFrequency($request->all());
        if (!$meetingFrequency) {
            return response()->json(['message' => 'Gagal membuat frekuensi pertemuan'], 400);
        }
        return new MeetingFrequencyResource($meetingFrequency);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $meetingFrequency = $this->meetingFrequencyService->getMeetingFrequencyById($id);
        if (!$meetingFrequency) {
            return response()->json(['message' => 'Frekuensi pertemuan tidak ditemukan'], 404);
        }
        return new MeetingFrequencyResource($meetingFrequency);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MeetingFrequencyUpdateRequest $request, string $id)
    {
        $meetingFrequency = $this->meetingFrequencyService->updateMeetingFrequency($id, $request->all());
        if (!$meetingFrequency) {
            return response()->json(['message' => 'Gagal mengubah frekuensi pertemuan'], 400);
        }
        return new MeetingFrequencyResource($meetingFrequency);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $deleted = $this->meetingFrequencyService->deleteMeetingFrequency($id);
        if (!$deleted) {
            return response()->json(['message' => 'Gagal menghapus frekuensi pertemuan'], 400);
        }
        return response()->json(null, 204);
    }
}
