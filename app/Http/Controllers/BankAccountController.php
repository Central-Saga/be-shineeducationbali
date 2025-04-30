<?php

namespace App\Http\Controllers;

use App\Http\Resources\BankAccountResource;
use App\Services\BankAccountService;
use Illuminate\Http\Request;

class BankAccountController extends Controller
{
    protected $bankAccountService;

    public function __construct(BankAccountService $bankAccountService)
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
            $bankAccounts = $this->bankAccountService->getAll();
        } elseif (strtolower($status) === 'aktif') {
            $bankAccounts = $this->bankAccountService->getBankAccountsByStatusAktif();
        } elseif (strtolower($status) === 'non aktif') {
            $bankAccounts = $this->bankAccountService->getBankAccountsByStatusNonAktif();
        } else {
            return response()->json(['error' => 'Invalid status parameter'], 400);
        }

        return BankAccountResource::collection($bankAccounts);
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
            'status' => 'required|in:Aktif,Non Aktif',
        ]);

        $bankAccount = $this->bankAccountService->create($validated);
        return new BankAccountResource($bankAccount);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $bankAccount = $this->bankAccountService->getById($id);
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
            'status' => 'sometimes|in:Aktif,Non Aktif',
        ]);

        $bankAccount = $this->bankAccountService->update($id, $validated);
        return new BankAccountResource($bankAccount);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->bankAccountService->delete($id);
        return response()->json(['message' => 'Bank account deleted successfully']);
    }
}
