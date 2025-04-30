<?php

namespace App\Http\Controllers;

use App\Http\Requests\NotificationStoreRequest;
use App\Http\Requests\NotificationUpdateRequest;
use App\Http\Resources\NotificationResource;
use App\Services\NotificationServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Instance dari NotificationServiceInterface.
     *
     * @var NotificationServiceInterface
     */
    protected $notificationService;

    /**
     * Konstruktor untuk menginisialisasi service.
     *
     * @param NotificationServiceInterface $notificationService
     */
    public function __construct(NotificationServiceInterface $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Menampilkan daftar data notification berdasarkan status, tipe, atau semua data.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        // Ambil parameter status dan type dari query string
        $status = $request->query('status');
        $type = $request->query('type');

        if ($status === null && $type === null) {
            // Jika tidak ada query parameter, ambil semua notification
            $notifications = $this->notificationService->getAllNotifications();
        } elseif ($status !== null) {
            // Filter berdasarkan status
            if ($status == 'read') {
                $notifications = $this->notificationService->findByStatusRead();
            } elseif ($status == 'unread') {
                $notifications = $this->notificationService->findByStatusUnread();
            } else {
                return response()->json([
                    'error' => 'Parameter status tidak valid. Gunakan "read" atau "unread".'
                ], 400);
            }
        } elseif ($type !== null) {
            // Filter berdasarkan tipe
            if ($type == 'Payment') {
                $notifications = $this->notificationService->findByTypePayment();
            } elseif ($type == 'Leave') {
                $notifications = $this->notificationService->findByTypeLeave();
            } else {
                return response()->json([
                    'error' => 'Parameter type tidak valid. Gunakan "Payment" atau "Leave".'
                ], 400);
            }
        }

        if (!$notifications || $notifications->isEmpty()) {
            return response()->json([
                'message' => 'Data notification tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Daftar data notification berhasil diambil',
            'data' => NotificationResource::collection($notifications)
        ], 200);
    }

    /**
     * Menyimpan data notification baru.
     *
     * @param NotificationStoreRequest $request
     * @return JsonResponse
     */
    public function store(NotificationStoreRequest $request): JsonResponse
    {
        $data = $request->validated();
        $notification = $this->notificationService->createNotification($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Data notification berhasil dibuat.',
            'data' => new NotificationResource($notification),
        ], 201);
    }

    /**
     * Menampilkan detail data notification berdasarkan ID.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $notification = $this->notificationService->getNotificationById($id);
            return response()->json([
                'status' => 'success',
                'message' => 'Detail data notification berhasil diambil.',
                'data' => new NotificationResource($notification),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data notification tidak ditemukan.',
            ], 404);
        }
    }

    /**
     * Memperbarui data notification berdasarkan ID.
     *
     * @param NotificationUpdateRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(NotificationUpdateRequest $request, int $id): JsonResponse
    {
        try {
            $data = $request->validated();
            $notification = $this->notificationService->updateNotification($id, $data);

            return response()->json([
                'status' => 'success',
                'message' => 'Data notification berhasil diperbarui.',
                'data' => new NotificationResource($notification),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data notification tidak ditemukan.',
            ], 404);
        }
    }

    /**
     * Menghapus data notification berdasarkan ID.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->notificationService->deleteNotification($id);

            return response()->json([
                'status' => 'success',
                'message' => 'Data notification berhasil dihapus.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data notification tidak ditemukan.',
            ], 404);
        }
    }
}
