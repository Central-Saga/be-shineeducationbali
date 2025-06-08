<?php

namespace App\Repositories\Contracts;

use App\Models\Leave;
use Illuminate\Database\Eloquent\Collection;

interface LeaveRepositoryInterface
{
    /**
     * Mendapatkan semua data leave.
     *
     * @return Collection
     */
    public function getAll(): Collection;

    /**
     * Mendapatkan data leave berdasarkan ID.
     *
     * @param int $id
     * @return Leave|null
     */
    public function findById(int $id): ?Leave;

    /**
     * Membuat data leave baru.
     *
     * @param array $data
     * @return Leave
     */
    public function create(array $data): Leave;

    /**
     * Memperbarui data leave berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return Leave
     */
    public function update(int $id, array $data): Leave;

    /**
     * Menghapus data leave berdasarkan ID.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;
}
