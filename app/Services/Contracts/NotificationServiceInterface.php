<?php

namespace App\Services;

use App\Models\Notification;
use Illuminate\Database\Eloquent\Collection;

interface NotificationServiceInterface
{
    /**
     * Mendapatkan semua data notification.
     *
     * @return Collection
     */
    public function getAllNotifications(): Collection;

    /**
     * Mendapatkan data notification berdasarkan ID.
     *
     * @param int $id
     * @return Notification
     */
    public function getNotificationById(int $id): Notification;

    /**
     * Mendapatkan data notification yang belum dibaca.
     *
     * @return Collection
     */
    public function getUnreadNotifications(): Collection;

    /**
     * Mendapatkan data notification berdasarkan tipe.
     *
     * @param string $type
     * @return Collection
     */
    public function getNotificationsByType(string $type): Collection;

    /**
     * Mendapatkan data notification dengan tipe Payment.
     *
     * @return Collection
     */
    public function findByTypePayment(): Collection;

    /**
     * Mendapatkan data notification dengan tipe Leave.
     *
     * @return Collection
     */
    public function findByTypeLeave(): Collection;

    /**
     * Mendapatkan data notification dengan status read.
     *
     * @return Collection
     */
    public function findByStatusRead(): Collection;

    /**
     * Mendapatkan data notification dengan status unread.
     *
     * @return Collection
     */
    public function findByStatusUnread(): Collection;

    /**
     * Membuat data notification baru.
     *
     * @param array $data
     * @return Notification
     */
    public function createNotification(array $data): Notification;

    /**
     * Memperbarui data notification berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return Notification
     */
    public function updateNotification(int $id, array $data): Notification;

    /**
     * Menghapus data notification berdasarkan ID.
     *
     * @param int $id
     * @return bool
     */
    public function deleteNotification(int $id): bool;
}
