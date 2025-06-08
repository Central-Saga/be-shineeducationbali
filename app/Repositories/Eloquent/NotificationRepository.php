<?php

namespace App\Repositories\Eloquent;

use App\Models\Notification;
use App\Repositories\Contracts\NotificationRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class NotificationRepository implements NotificationRepositoryInterface
{
    /**
     * Instance dari model Notification.
     *
     * @var Notification
     */
    protected $model;

    /**
     * Konstruktor untuk menginisialisasi model.
     *
     * @param Notification $model
     */
    public function __construct(Notification $model)
    {
        $this->model = $model;
    }

    /**
     * Mendapatkan semua data notification.
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return $this->model->with('user')->get();
    }

    /**
     * Mendapatkan data notification berdasarkan ID.
     *
     * @param int $id
     * @return Notification|null
     */
    public function findById(int $id): ?Notification
    {
        return $this->model->with('user')->findOrFail($id);
    }

    /**
     * Mendapatkan data notification berdasarkan tipe.
     *
     * @param string $type
     * @return Collection
     */
    public function findByType(string $type): Collection
    {
        return $this->model->with('user')
            ->where('type', $type)
            ->get();
    }

    /**
     * Mendapatkan data notification berdasarkan status.
     *
     * @param string $status
     * @return Collection
     */
    public function findByStatus(string $status): Collection
    {
        return $this->model->with('user')
            ->where('status', $status)
            ->get();
    }

    /**
     * Membuat data notification baru.
     *
     * @param array $data
     * @return Notification
     */
    public function create(array $data): Notification
    {
        return $this->model->create($data);
    }

    /**
     * Memperbarui data notification berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return Notification
     */
    public function update(int $id, array $data): Notification
    {
        $notification = $this->findById($id);
        $notification->update($data);
        return $notification;
    }

    /**
     * Menghapus data notification berdasarkan ID.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $notification = $this->findById($id);
        return $notification->delete();
    }
}
