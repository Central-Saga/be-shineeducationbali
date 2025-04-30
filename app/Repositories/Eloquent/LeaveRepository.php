<?php

namespace App\Repositories;

use App\Models\Leave;
use Illuminate\Database\Eloquent\Collection;

class LeaveRepository implements LeaveRepositoryInterface
{
    /**
     * Mendapatkan semua data leave.
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return Leave::with('user')->get();
    }

    /**
     * Mendapatkan data leave berdasarkan ID.
     *
     * @param int $id
     * @return Leave|null
     */
    public function findById(int $id): ?Leave
    {
        return Leave::with('user')->findOrFail($id);
    }

    /**
     * Membuat data leave baru.
     *
     * @param array $data
     * @return Leave
     */
    public function create(array $data): Leave
    {
        return Leave::create($data);
    }

    /**
     * Memperbarui data leave berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return Leave
     */
    public function update(int $id, array $data): Leave
    {
        $leave = $this->findById($id);
        $leave->update($data);
        return $leave;
    }

    /**
     * Menghapus data leave berdasarkan ID.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $leave = $this->findById($id);
        return $leave->delete();
    }
}
