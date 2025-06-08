<?php

namespace App\Repositories\Contracts;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Collection;

interface TransactionRepositoryInterface
{
    /**
     * Get all transactions
     *
     * @return Collection
     */
    public function getAll(): Collection;

    /**
     * Get transaction by id
     *
     * @param int $id
     * @return Transaction|null
     */
    public function getById(int $id): ?Transaction;

    /**
     * Get transaction by name
     *
     * @param string $name
     * @return Collection
     */
    public function getByName(string $name): Collection;

    /**
     * Get transaction by status
     *
     * @param string $status
     * @return Collection
     */
    public function getByStatus(string $status): Collection;

    /**
     * Create transaction
     *
     * @param array $data
     * @return Transaction
     */
    public function create(array $data): Transaction;

    /**
     * Update transaction
     *
     * @param int $id
     * @param array $data
     * @return Transaction|null
     */
    public function update(int $id, array $data): ?Transaction;

    /**
     * Delete transaction
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;
}
