<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionDetailStoreRequest;
use App\Http\Requests\TransactionDetailUpdateRequest;
use App\Http\Resources\TransactionDetailResource;
use App\Services\TransactionDetailService;
use Illuminate\Http\Request;

class TransactionDetailController extends Controller
{
    protected $transactionDetailService;

    public function __construct(TransactionDetailService $transactionDetailService)
    {
        $this->transactionDetailService = $transactionDetailService;
    }

    public function index(Request $request)
    {
        $transactionDetails = $this->transactionDetailService->getAll();
        return TransactionDetailResource::collection($transactionDetails);
    }

    public function store(TransactionDetailStoreRequest $request)
    {
        $transactionDetail = $this->transactionDetailService->create($request->validated());
        return new TransactionDetailResource($transactionDetail);
    }

    public function show($id)
    {
        $transactionDetail = $this->transactionDetailService->getById($id);
        return new TransactionDetailResource($transactionDetail);
    }

    public function update(TransactionDetailUpdateRequest $request, $id)
    {
        $transactionDetail = $this->transactionDetailService->update($id, $request->validated());
        return new TransactionDetailResource($transactionDetail);
    }

    public function destroy($id)
    {
        $this->transactionDetailService->delete($id);
        return response()->json(['message' => 'Transaction detail deleted successfully']);
    }

    public function updateType(Request $request, $id)
    {
        $validated = $request->validate([
            'type' => 'required|in:Salary,Program_Payment,Deduction,Expenditure',
        ]);

        $transactionDetail = $this->transactionDetailService->update($id, ['type' => $validated['type']]);
        return new TransactionDetailResource($transactionDetail);
    }
}
