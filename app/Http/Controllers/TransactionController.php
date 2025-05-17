<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionStoreRequest;
use App\Http\Requests\TransactionUpdateRequest;
use App\Http\Resources\TransactionResource;
use App\Services\Implementations\TransactionService;
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
            $transactions = $this->transactionService->getAllTransactions();
        } elseif ($status === '0') {
            $transactions = $this->transactionService->getTransactionsByStatus('unpaid');
        } elseif ($status === '1') {
            $transactions = $this->transactionService->getTransactionsByStatus('paid');
        } else {
            return response()->json([
                'error' => 'Invalid status parameter',
                'message' => 'Status must be: 0 (unpaid) or 1 (paid)'
            ], 400);
        }

        return TransactionResource::collection($transactions);
    }

    public function store(TransactionStoreRequest $request)
    {
        $transaction = $this->transactionService->createTransaction($request->validated());
        return new TransactionResource($transaction);
    }

    public function show($id)
    {
        $transaction = $this->transactionService->getTransactionById($id);
        return new TransactionResource($transaction);
    }

    public function update(TransactionUpdateRequest $request, $id)
    {
        $transaction = $this->transactionService->updateTransaction($id, $request->validated());
        return new TransactionResource($transaction);
    }

    public function destroy($id)
    {
        $this->transactionService->deleteTransaction($id);
        return response()->json(['message' => 'Transaction deleted successfully']);
    }

    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:0,1',
        ]);

        // Convert numeric status to string
        $status = $validated['status'] === '1' ? 'paid' : 'unpaid';

        $transaction = $this->transactionService->updateTransaction($id, ['status' => $status]);
        return new TransactionResource($transaction);
    }
}
