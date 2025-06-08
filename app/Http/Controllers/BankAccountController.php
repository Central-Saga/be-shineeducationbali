<?php

namespace App\Http\Controllers;

use App\Http\Resources\BankAccountResource;
use App\Services\Contracts\BankAccountServiceInterface;
use App\Services\Implementations\BankAccountService;
use Illuminate\Http\Request;

class BankAccountController extends Controller
{
    protected $bankAccountService;

    public function __construct(BankAccountServiceInterface $bankAccountService)
    {
        $this->bankAccountService = $bankAccountService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $status = $request->query('status');

        if ($status === null) {
            $bankAccounts = $this->bankAccountService->getAllBankAccounts();
        } elseif ($status === '1') {
            $bankAccounts = $this->bankAccountService->getBankAccountsByStatus('Aktif');
        } elseif ($status === '0') {
            $bankAccounts = $this->bankAccountService->getBankAccountsByStatus('Non Aktif');
        } else {
            return response()->json(['error' => 'Invalid status parameter. Use 1 for active or 0 for inactive accounts'], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Bank accounts retrieved successfully',
            'data' => BankAccountResource::collection($bankAccounts)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'bank_name' => 'required|string|max:50',
            'account_number' => 'required|string|max:50',
            'account_holder' => 'required|string|max:100',
            'account_type' => 'required|in:Receipt,Expenditure',
            'status' => 'required|in:0,1',
        ]);

        // Convert numeric status to Aktif/Non Aktif for database
        $validated['status'] = $validated['status'] === '1' ? 'Aktif' : 'Non Aktif';

        $bankAccount = $this->bankAccountService->createBankAccount($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Bank account created successfully',
            'data' => new BankAccountResource($bankAccount)
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $bankAccount = $this->bankAccountService->getBankAccountById($id);
        return new BankAccountResource($bankAccount);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'bank_name' => 'sometimes|string|max:50',
            'account_number' => 'sometimes|string|max:50',
            'account_holder' => 'sometimes|string|max:100',
            'account_type' => 'sometimes|in:Receipt,Expenditure',
            'status' => 'sometimes|in:0,1',
        ]);

        // Convert numeric status to Aktif/Non Aktif for database if status is being updated
        if (isset($validated['status'])) {
            $validated['status'] = $validated['status'] === '1' ? 'Aktif' : 'Non Aktif';
        }

        $bankAccount = $this->bankAccountService->updateBankAccount($id, $validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Bank account updated successfully',
            'data' => new BankAccountResource($bankAccount)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->bankAccountService->deleteBankAccount($id);
        return response()->json(['message' => 'Bank account deleted successfully']);
    }
}
