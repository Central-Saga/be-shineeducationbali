<?php

namespace App\Services\Implementations;

use App\Repositories\Contracts\NotificationRepositoryInterface;
use App\Models\Notification;
use App\Services\Contracts\NotificationServiceInterface;
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
     * Cache key constants.
     */
    private const CACHE_KEYS = [
        'ALL' => 'notifications.all',
        'UNREAD' => 'notifications.unread',
        'STATUS' => 'notifications.status.',
        'TYPE' => 'notifications.type.',
        'BY_ID' => 'notifications.id.',
    ];

    /**
     * Cache duration in seconds (1 hour).
     */
    private const CACHE_DURATION = 3600;

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
        return Cache::remember(self::CACHE_KEYS['ALL'], self::CACHE_DURATION, function () {
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
        return Cache::remember(self::CACHE_KEYS['BY_ID'] . $id, self::CACHE_DURATION, function () use ($id) {
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
        return Cache::remember(self::CACHE_KEYS['UNREAD'], self::CACHE_DURATION, function () {
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
        return Cache::remember(self::CACHE_KEYS['TYPE'] . strtolower($type), self::CACHE_DURATION, function () use ($type) {
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
        return Cache::remember(self::CACHE_KEYS['TYPE'] . 'payment', self::CACHE_DURATION, function () {
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
        return Cache::remember(self::CACHE_KEYS['TYPE'] . 'leave', self::CACHE_DURATION, function () {
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
        return Cache::remember(self::CACHE_KEYS['STATUS'] . 'read', self::CACHE_DURATION, function () {
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
        return Cache::remember(self::CACHE_KEYS['STATUS'] . 'unread', self::CACHE_DURATION, function () {
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
        Cache::forget(self::CACHE_KEYS['ALL']);
        Cache::forget(self::CACHE_KEYS['UNREAD']);
        Cache::forget(self::CACHE_KEYS['STATUS'] . 'read');
        Cache::forget(self::CACHE_KEYS['STATUS'] . 'unread');
        Cache::forget(self::CACHE_KEYS['TYPE'] . 'payment');
        Cache::forget(self::CACHE_KEYS['TYPE'] . 'leave');
        Cache::forget(self::CACHE_KEYS['TYPE'] . 'grade');
    }
}
