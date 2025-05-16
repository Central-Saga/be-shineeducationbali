<?php

namespace App\Services\Contracts;

use App\Models\Leave;
use Illuminate\Database\Eloquent\Collection;

interface LeaveServiceInterface
{
    /**
     * Mendapatkan semua data leave.
     *
     * @return Collection
     */
    public function getAllLeaves(): Collection;

    /**
     * Mendapatkan data leave berdasarkan ID.
     *
     * @param int $id
     * @return Leave
     */
    public function getLeaveById(int $id): Leave;

    /**
     * Mendapatkan data leave berdasarkan nama pengguna.
     *
     * @param string $name
     * @return Collection
     */
    public function getLeaveByName(string $name): Collection;

    /**
     * Mendapatkan data leave berdasarkan status.
     *
     * @param int $status 0: ditolak, 1: disetujui, 2: menunggu konfirmasi
     * @return Collection
     */
    public function getLeaveByStatus(int $status): Collection;

    /**
     * Mendapatkan data leave dengan status ditolak.
     *
     * @return Collection
     */
    public function getLeaveByRejected(): Collection;

    /**
     * Mendapatkan data leave dengan status disetujui.
     *
     * @return Collection
     */
    public function getLeaveByConfirmation(): Collection;

    /**
     * Mendapatkan data leave dengan status menunggu konfirmasi.
     *
     * @return Collection
     */
    public function getLeaveByWaiting(): Collection;

    /**
     * Membuat data leave baru.
     *
     * @param array $data
     * @return Leave
     */
    public function createLeave(array $data): Leave;

    /**
     * Memperbarui data leave berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return Leave
     */
    public function updateLeave(int $id, array $data): Leave;

    /**
     * Menghapus data leave berdasarkan ID.
     *
     * @param int $id
     * @return bool
     */
    public function deleteLeave(int $id): bool;
}
