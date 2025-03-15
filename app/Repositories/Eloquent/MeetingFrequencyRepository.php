<?php

namespace App\Repositories\Eloquent;

use App\Models\MeetingFrequency;
use Illuminate\Support\Facades\Log;
use App\Repositories\Contracts\MeetingFrequencyRepositoryInterface;

class MeetingFrequencyRepository implements MeetingFrequencyRepositoryInterface
{
    protected $model;

    public function __construct(MeetingFrequency $model)
    {
        $this->model = $model;
    }

    /**
     * Mengambil semua frekuensi pertemuan.
     *
     * @return mixed
     */
    public function getAllMeetingFrequencies()
    {
        return $this->model->all();
    }

    /**
     * Mengambil frekuensi pertemuan berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getMeetingFrequencyById($id)
    {
        try {
            // Mengambil permission berdasarkan ID, handle jika tidak ditemukan
            return $this->model->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("Meeting frequency with ID {$id} not found.");
            return null;
        }
    }

    /**
     * Mengambil frekuensi pertemuan berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getMeetingFrequencyByName($name)
    {
        return $this->model->where('name', $name)->first();
    }

    /**
     * Membuat frekuensi pertemuan baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createMeetingFrequency(array $data)
    {
        try {
            return $this->model->create($data);
        } catch (\Exception $e) {
            Log::error("Failed to create meeting frequency: {$e->getMessage()}");
            return null;
        }
    }

    /**
     * Memperbarui frekuensi pertemuan berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateMeetingFrequency($id, array $data)
    {
        $meetingFrequency = $this->findMeetingFrequency($id);

        if ($meetingFrequency) {
            try {
                $meetingFrequency->update($data);
                return $meetingFrequency;
            } catch (\Exception $e) {
                Log::error("Failed to update meeting frequency with ID {$id}: {$e->getMessage()}");
                return null;
            }
        }
        return null;
    }

    /**
     * Menghapus frekuensi pertemuan berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteMeetingFrequency($id)
    {
        $meetingFrequency = $this->findMeetingFrequency($id);

        if ($meetingFrequency) {
            try {
                $meetingFrequency->delete();
                return true;
            } catch (\Exception $e) {
                Log::error("Failed to delete meeting frequency with ID {$id}: {$e->getMessage()}");
                return false;
            }
        }
        return false;
    }

    /**
     * Helper method untuk menemukan frekuensi pertemuan berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    protected function findMeetingFrequency($id)
    {
        try {
            return $this->model->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("Meeting frequency with ID {$id} not found.");
            return null;
        }
    }
}
