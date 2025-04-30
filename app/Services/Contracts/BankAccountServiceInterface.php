<?php

namespace App\Services\Contracts;

use App\Models\BankAccount;
use Illuminate\Database\Eloquent\Collection;

interface BankAccountServiceInterface
{
    /**
     * Get all bank accounts
     *
     * @return Collection
     */
    public function getAllBankAccounts(): Collection;

    /**
     * Get bank account by ID
     *
     * @param int $id
     * @return BankAccount|null
     */
    public function getBankAccountById($id): ?BankAccount;

    /**
     * Get bank account by name
     *
     * @param string $name
     * @return Collection
     */
    public function getBankAccountByName($name): Collection;

    /**
     * Get bank accounts by status
     *
     * @param string $status
     * @return Collection
     */
    public function getBankAccountsByStatus($status): Collection;

    /**
     * Create new bank account
     *
     * @param array $data
     * @return BankAccount
     */
    public function createBankAccount(array $data): BankAccount;

    /**
     * Update bank account
     *
     * @param int $id
     * @param array $data
     * @return BankAccount|null
     */
    public function updateBankAccount($id, array $data): ?BankAccount;

    /**
     * Delete bank account
     *
     * @param int $id
     * @return bool
     */
    public function deleteBankAccount($id): bool;
}
