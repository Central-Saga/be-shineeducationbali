<?php

namespace App\Services;

use App\Repositories\NotificationRepositoryInterface;
use App\Models\Notification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class NotificationService implements NotificationServiceInterface
{
    /**
     * Instance dari NotificationRepositoryInterface.
     *
     * @var NotificationRepositoryInterface
     */
    protected $notificationRepository;

    /**
     * Konstruktor untuk menginisialisasi repository.
     *
     * @param NotificationRepositoryInterface $notificationRepository
     */
    public function __construct(NotificationRepositoryInterface $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }

    /**
     * Mendapatkan semua data notification dengan cache.
     *
     * @return Collection
     */
    public function getAllNotifications(): Collection
    {
        return Cache::remember('notifications_all', 60 * 60, function () {
            return $this->notificationRepository->getAll();
        });
    }

    /**
     * Mendapatkan data notification berdasarkan ID.
     *
     * @param int $id
     * @return Notification
     */
    public function getNotificationById(int $id): Notification
    {
        return Cache::remember("notification_{$id}", 60 * 60, function () use ($id) {
            return $this->notificationRepository->findById($id);
        });
    }

    /**
     * Mendapatkan data notification yang belum dibaca.
     *
     * @return Collection
     */
    public function getUnreadNotifications(): Collection
    {
        return Cache::remember('notifications_unread', 60 * 60, function () {
            return $this->notificationRepository->findByStatus('unread');
        });
    }

    /**
     * Mendapatkan data notification berdasarkan tipe.
     *
     * @param string $type
     * @return Collection
     */
    public function getNotificationsByType(string $type): Collection
    {
        return Cache::remember("notifications_by_type_{$type}", 60 * 60, function () use ($type) {
            return $this->notificationRepository->findByType($type);
        });
    }

    /**
     * Mendapatkan data notification dengan tipe Payment.
     *
     * @return Collection
     */
    public function findByTypePayment(): Collection
    {
        return Cache::remember('notifications_type_payment', 60 * 60, function () {
            return $this->notificationRepository->findByType('Payment');
        });
    }

    /**
     * Mendapatkan data notification dengan tipe Leave.
     *
     * @return Collection
     */
    public function findByTypeLeave(): Collection
    {
        return Cache::remember('notifications_type_leave', 60 * 60, function () {
            return $this->notificationRepository->findByType('Leave');
        });
    }

    /**
     * Mendapatkan data notification dengan status read.
     *
     * @return Collection
     */
    public function findByStatusRead(): Collection
    {
        return Cache::remember('notifications_status_read', 60 * 60, function () {
            return $this->notificationRepository->findByStatus('read');
        });
    }

    /**
     * Mendapatkan data notification dengan status unread.
     *
     * @return Collection
     */
    public function findByStatusUnread(): Collection
    {
        return Cache::remember('notifications_status_unread', 60 * 60, function () {
            return $this->notificationRepository->findByStatus('unread');
        });
    }

    /**
     * Membuat data notification baru dan menghapus cache terkait.
     *
     * @param array $data
     * @return Notification
     */
    public function createNotification(array $data): Notification
    {
        $notification = $this->notificationRepository->create($data);
        $this->clearCache();
        return $notification;
    }

    /**
     * Memperbarui data notification berdasarkan ID dan menghapus cache terkait.
     *
     * @param int $id
     * @param array $data
     * @return Notification
     */
    public function updateNotification(int $id, array $data): Notification
    {
        $notification = $this->notificationRepository->update($id, $data);
        $this->clearCache();
        return $notification;
    }

    /**
     * Menghapus data notification berdasarkan ID dan menghapus cache terkait.
     *
     * @param int $id
     * @return bool
     */
    public function deleteNotification(int $id): bool
    {
        $result = $this->notificationRepository->delete($id);
        $this->clearCache();
        return $result;
    }

    /**
     * Menghapus cache terkait data notification.
     *
     * @return void
     */
    protected function clearCache(): void
    {
        Cache::forget('notifications_all');
        Cache::forget('notifications_unread');
        Cache::forget('notifications_status_read');
        Cache::forget('notifications_status_unread');
        Cache::forget('notifications_type_payment');
        Cache::forget('notifications_type_leave');
        // Untuk efisiensi, kita bisa menghapus cache spesifik berdasarkan ID atau tipe,
        // tetapi untuk saat ini kita hapus semua cache terkait notification.
        Cache::tags(['notifications'])->flush();
    }
}
