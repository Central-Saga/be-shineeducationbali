<?php

namespace App\Repositories\Eloquent;

use App\Models\Schedule;
use Illuminate\Support\Facades\Log;
use App\Repositories\Contracts\ScheduleRepositoryInterface;

class ScheduleRepository implements ScheduleRepositoryInterface
{
    protected $model;

    public function __construct(Schedule $model)
    {
        $this->model = $model;
    }

    /**
     * Mengambil semua schedules.
     *
     * @return mixed
     */
    public function getAllSchedules()
    {
        return $this->model->with('classRoom')->get();
    }

    /**
     * Mengambil schedule berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getScheduleById($id)
    {
        try {
            // Mengambil permission berdasarkan ID, handle jika tidak ditemukan
            return $this->model->with('classRoom')->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("Schedule with ID {$id} not found.");
            return null;
        }
    }

    /**
     * Mengambil schedule berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getScheduleByName($name)
    {
        return $this->model->with('classRoom')->where('name', $name)->first();
    }

    /**
     * Membuat schedule baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createSchedule(array $data)
    {
        try {
            $schedule = $this->model->create($data);
            return $schedule->load('classRoom');
        } catch (\Exception $e) {
            Log::error("Failed to create schedule: {$e->getMessage()}");
            return null;
        }
    }

    /**
     * Memperbarui schedule berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateSchedule($id, array $data)
    {
        $schedule = $this->findSchedule($id);

        if ($schedule) {
            try {
                $schedule->update($data);
                return $schedule->load('classRoom');
            } catch (\Exception $e) {
                Log::error("Failed to update schedule with ID {$id}: {$e->getMessage()}");
                return null;
            }
        }
        return null;
    }

    /**
     * Menghapus schedule berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteSchedule($id)
    {
        $schedule = $this->findSchedule($id);

        if ($schedule) {
            try {
                $schedule->delete();
                return true;
            } catch (\Exception $e) {
                Log::error("Failed to delete schedule with ID {$id}: {$e->getMessage()}");
                return false;
            }
        }
        return false;
    }

    /**
     * Helper method untuk menemukan schedule berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    protected function findSchedule($id)
    {
        try {
            return $this->model->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("Schedule with ID {$id} not found.");
            return null;
        }
    }
}
