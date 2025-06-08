<?php

namespace App\Repositories\Contracts;

use App\Models\BankAccount;
use Illuminate\Database\Eloquent\Collection;

interface BankAccountRepositoryInterface
{
    /**
     * Get all bank accounts
     * 
     * @return Collection
     */
    public function getAll(): Collection;

    /**
     * Get bank account by id
     * 
     * @param int $id
     * @return BankAccount|null
     */
    public function getById($id): ?BankAccount;

    /**
     * Get bank account by name
     * 
     * @param string $name
     * @return Collection
     */
    public function getByName($name): Collection;

    /**
     * Get bank account by status
     * 
     * @param string $status
     * @return Collection
     */
    public function getByStatus($status): Collection;

    /**
     * Create bank account
     * 
     * @param array $data
     * @return BankAccount
     */
    public function create(array $data): BankAccount;

    /**
     * Update bank account
     * 
     * @param int $id
     * @param array $data
     * @return BankAccount|null
     */
    public function update($id, array $data): ?BankAccount;

    /**
     * Delete bank account
     * 
     * @param int $id
     * @return bool
     */
    public function delete($id): bool;
}
