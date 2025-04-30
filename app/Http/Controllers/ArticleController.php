<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArticleStoreRequest;
use App\Http\Requests\ArticleUpdateRequest;
use App\Http\Resources\ArticleResource;
use App\Services\ArticleServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Instance dari ArticleServiceInterface.
     *
     * @var ArticleServiceInterface
     */
    protected $articleService;

    /**
     * Konstruktor untuk menginisialisasi service.
     *
     * @param ArticleServiceInterface $articleService
     */
    public function __construct(ArticleServiceInterface $articleService)
    {
        $this->articleService = $articleService;
    }

    /**
     * Menampilkan daftar data article, dengan opsi untuk mengambil artikel terbaru.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        // Ambil parameter recent dari query string
        $recent = $request->query('recent');

        if ($recent === null) {
            // Jika tidak ada query parameter, ambil semua article
            $articles = $this->articleService->getAllArticles();
        } elseif ($recent === 'true') {
            // Jika recent=true, ambil artikel terbaru (default limit 10)
            $limit = $request->query('limit', 10); // Ambil parameter limit, default 10
            $articles = $this->articleService->getRecentArticles((int) $limit);
        } else {
            return response()->json([
                'error' => 'Parameter recent tidak valid. Gunakan "true" untuk mengambil artikel terbaru.'
            ], 400);
        }

        if (!$articles || $articles->isEmpty()) {
            return response()->json([
                'message' => 'Data article tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Daftar data article berhasil diambil',
            'data' => ArticleResource::collection($articles)
        ], 200);
    }

    /**
     * Menyimpan data article baru.
     *
     * @param ArticleStoreRequest $request
     * @return JsonResponse
     */
    public function store(ArticleStoreRequest $request): JsonResponse
    {
        $data = $request->validated();
        $article = $this->articleService->createArticle($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Data article berhasil dibuat.',
            'data' => new ArticleResource($article),
        ], 201);
    }

    /**
     * Menampilkan detail data article berdasarkan ID.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $article = $this->articleService->getArticleById($id);
            return response()->json([
                'status' => 'success',
                'message' => 'Detail data article berhasil diambil.',
                'data' => new ArticleResource($article),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data article tidak ditemukan.',
            ], 404);
        }
    }

    /**
     * Memperbarui data article berdasarkan ID.
     *
     * @param ArticleUpdateRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(ArticleUpdateRequest $request, int $id): JsonResponse
    {
        try {
            $data = $request->validated();
            $article = $this->articleService->updateArticle($id, $data);

            return response()->json([
                'status' => 'success',
                'message' => 'Data article berhasil diperbarui.',
                'data' => new ArticleResource($article),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data article tidak ditemukan.',
            ], 404);
        }
    }

    /**
     * Menghapus data article berdasarkan ID.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->articleService->deleteArticle($id);

            return response()->json([
                'status' => 'success',
                'message' => 'Data article berhasil dihapus.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data article tidak ditemukan.',
            ], 404);
        }
    }
}
