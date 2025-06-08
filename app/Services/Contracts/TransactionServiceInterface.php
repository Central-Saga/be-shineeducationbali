<?php

namespace App\Services\Contracts;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Collection;

interface TransactionServiceInterface
{
    /**
     * Get all transactions
     *
     * @return Collection
     */
    public function getAllTransactions(): Collection;

    /**
     * Get transaction by ID
     *
     * @param int $id
     * @return Transaction|null
     */
    public function getTransactionById($id): ?Transaction;

    /**
     * Get transactions by name
     *
     * @param string $name
     * @return Collection
     */
    public function getTransactionsByName($name): Collection;

    /**
     * Get transactions by status
     *
     * @param string $status
     * @return Collection
     */
    public function getTransactionsByStatus($status): Collection;

    /**
     * Create new transaction
     *
     * @param array $data
     * @return Transaction
     */
    public function createTransaction(array $data): Transaction;

    /**
     * Update transaction
     *
     * @param int $id
     * @param array $data
     * @return Transaction|null
     */
    public function updateTransaction($id, array $data): ?Transaction;

    /**
     * Delete transaction
     *
     * @param int $id
     * @return bool
     */
    public function deleteTransaction($id): bool;

    /**
     * Get transactions by student ID
     *
     * @param int $studentId
     * @return Collection
     */
    public function getTransactionsByStudentId($studentId): Collection;

    /**
     * Get transactions by teacher ID
     *
     * @param int $teacherId
     * @return Collection
     */
    public function getTransactionsByTeacherId($teacherId): Collection;
}
