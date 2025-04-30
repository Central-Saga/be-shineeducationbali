<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionStoreRequest;
use App\Http\Requests\TransactionUpdateRequest;
use App\Http\Resources\TransactionResource;
use App\Services\TransactionService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function index(Request $request)
    {
        $status = $request->query('status');

        if ($status === null) {
            $transactions = $this->transactionService->getAll();
        } elseif (strtolower($status) === 'paid') {
            $transactions = $this->transactionService->getTransactionsByStatusPaid();
        } elseif (strtolower($status) === 'unpaid') {
            $transactions = $this->transactionService->getTransactionsByStatusUnpaid();
        } else {
            return response()->json(['error' => 'Invalid status parameter'], 400);
        }

        return TransactionResource::collection($transactions);
    }

    public function store(TransactionStoreRequest $request)
    {
        $transaction = $this->transactionService->create($request->validated());
        return new TransactionResource($transaction);
    }

    public function show($id)
    {
        $transaction = $this->transactionService->getById($id);
        return new TransactionResource($transaction);
    }

    public function update(TransactionUpdateRequest $request, $id)
    {
        $transaction = $this->transactionService->update($id, $request->validated());
        return new TransactionResource($transaction);
    }

    public function destroy($id)
    {
        $this->transactionService->delete($id);
        return response()->json(['message' => 'Transaction deleted successfully']);
    }

    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:paid,unpaid',
        ]);

        $transaction = $this->transactionService->update($id, ['status' => $validated['status']]);
        return new TransactionResource($transaction);
    }
}
