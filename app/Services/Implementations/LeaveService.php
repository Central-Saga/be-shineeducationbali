<?php

namespace App\Services\Implementations;

use App\Repositories\Contracts\LeaveRepositoryInterface;
use App\Services\Contracts\LeaveServiceInterface;
use App\Models\Leave;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class LeaveService implements LeaveServiceInterface
{
    /**
     * Instance dari LeaveRepositoryInterface.
     *
     * @var LeaveRepositoryInterface
     */
    protected $leaveRepository;

    /**
     * Konstruktor untuk menginisialisasi repository.
     *
     * @param LeaveRepositoryInterface $leaveRepository
     */
    public function __construct(LeaveRepositoryInterface $leaveRepository)
    {
        $this->leaveRepository = $leaveRepository;
    }

    /**
     * Mendapatkan semua data leave dengan cache.
     *
     * @return Collection
     */
    public function getAllLeaves(): Collection
    {
        return Cache::remember('leaves_all', 60 * 60, function () {
            return $this->leaveRepository->getAll();
        });
    }

    /**
     * Mendapatkan data leave berdasarkan ID.
     *
     * @param int $id
     * @return Leave
     */
    public function getLeaveById(int $id): Leave
    {
        return Cache::remember("leave_{$id}", 60 * 60, function () use ($id) {
            return $this->leaveRepository->findById($id);
        });
    }

    /**
     * Mendapatkan data leave berdasarkan nama pengguna.
     *
     * @param string $name
     * @return Collection
     */
    public function getLeaveByName(string $name): Collection
    {
        return Cache::remember("leaves_by_name_{$name}", 60 * 60, function () use ($name) {
            return Leave::with('user')
                ->whereHas('user', function ($query) use ($name) {
                    $query->where('username', 'like', "%{$name}%");
                })
                ->get();
        });
    }

    /**
     * Mendapatkan data leave berdasarkan status.
     *
     * @param int $status 0: ditolak, 1: disetujui, 2: menunggu konfirmasi
     * @return Collection
     */
    public function getLeaveByStatus(int $status): Collection
    {
        if (!array_key_exists($status, Leave::$statusMap)) {
            throw new \InvalidArgumentException('Parameter status tidak valid. Gunakan 0 (ditolak), 1 (disetujui), atau 2 (menunggu konfirmasi).');
        }

        $statusString = Leave::getStatusString($status);

        return Cache::remember("leaves_by_status_{$status}", 60 * 60, function () use ($statusString) {
            return Leave::with('user')
                ->where('status', $statusString)
                ->get();
        });
    }

    /**
     * Mendapatkan data leave dengan status ditolak.
     *
     * @return Collection
     */
    public function getLeaveByRejected(): Collection
    {
        return $this->getLeaveByStatus(Leave::STATUS_REJECTED);
    }

    /**
     * Mendapatkan data leave dengan status disetujui.
     *
     * @return Collection
     */
    public function getLeaveByConfirmation(): Collection
    {
        return $this->getLeaveByStatus(Leave::STATUS_APPROVED);
    }

    /**
     * Mendapatkan data leave dengan status menunggu konfirmasi.
     *
     * @return Collection
     */
    public function getLeaveByWaiting(): Collection
    {
        return $this->getLeaveByStatus(Leave::STATUS_PENDING);
    }

    /**
     * Membuat data leave baru dan menghapus cache terkait.
     *
     * @param array $data
     * @return Leave
     */
    public function createLeave(array $data): Leave
    {
        $leave = $this->leaveRepository->create($data);
        $this->clearCache();
        return $leave;
    }

    /**
     * Memperbarui data leave berdasarkan ID dan menghapus cache terkait.
     *
     * @param int $id
     * @param array $data
     * @return Leave
     */
    public function updateLeave(int $id, array $data): Leave
    {
        $leave = $this->leaveRepository->update($id, $data);
        $this->clearCache();
        return $leave;
    }

    /**
     * Menghapus data leave berdasarkan ID dan menghapus cache terkait.
     *
     * @param int $id
     * @return bool
     */
    public function deleteLeave(int $id): bool
    {
        $result = $this->leaveRepository->delete($id);
        $this->clearCache();
        return $result;
    }

    /**
     * Menghapus cache terkait data leave.
     *
     * @return void
     */
    protected function clearCache(): void
    {
        // Hapus cache untuk semua data
        Cache::forget('leaves_all');
        
        // Hapus cache untuk status
        foreach ([0, 1, 2] as $status) {
            Cache::forget("leaves_by_status_{$status}");
        }
        
        // Hapus cache untuk data spesifik jika ada
        if (isset($this->id)) {
            Cache::forget("leave_{$this->id}");
        }
    }
}
