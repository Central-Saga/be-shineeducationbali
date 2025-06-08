<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionDetailStoreRequest;
use App\Http\Requests\TransactionDetailUpdateRequest;
use App\Http\Resources\TransactionDetailResource;
use App\Services\Contracts\TransactionDetailServiceInterface;
use Illuminate\Http\Request;

class TransactionDetailController extends Controller
{
    protected $transactionDetailService;

    public function __construct(TransactionDetailServiceInterface $transactionDetailService)
    {
        $this->transactionDetailService = $transactionDetailService;
    }

    public function index(Request $request)
    {
        $transactionDetails = $this->transactionDetailService->getAllTransactionDetails();
        return TransactionDetailResource::collection($transactionDetails);
    }

    public function store(TransactionDetailStoreRequest $request)
    {
        $transactionDetail = $this->transactionDetailService->createTransactionDetail($request->validated());
        return new TransactionDetailResource($transactionDetail);
    }

    public function show($id)
    {
        $transactionDetail = $this->transactionDetailService->getTransactionDetailById($id);
        return new TransactionDetailResource($transactionDetail);
    }

    public function update(TransactionDetailUpdateRequest $request, $id)
    {
        $transactionDetail = $this->transactionDetailService->updateTransactionDetail($id, $request->validated());
        return new TransactionDetailResource($transactionDetail);
    }

    public function destroy($id)
    {
        $this->transactionDetailService->deleteTransactionDetail($id);
        return response()->json(['message' => 'Transaction detail deleted successfully']);
    }

    public function updateType(Request $request, $id)
    {
        $validated = $request->validate([
            'type' => 'required|in:Salary,Program_Payment,Deduction,Expenditure',
        ]);

        $transactionDetail = $this->transactionDetailService->updateTransactionDetail($id, ['type' => $validated['type']]);
        return new TransactionDetailResource($transactionDetail);
    }
}
