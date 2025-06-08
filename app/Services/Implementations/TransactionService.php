<?php

namespace App\Services\Implementations;

use App\Models\Transaction;
use App\Repositories\Contracts\TransactionRepositoryInterface;
use App\Services\Contracts\TransactionServiceInterface;
use Illuminate\Database\Eloquent\Collection;

class TransactionService implements TransactionServiceInterface
{
    protected $repository;

    /**
     * TransactionService constructor.
     *
     * @param TransactionRepositoryInterface $repository
     */
    public function __construct(TransactionRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get all transactions
     *
     * @return Collection
     */
    public function getAllTransactions(): Collection
    {
        return $this->repository->getAll();
    }

    /**
     * Get transaction by ID
     *
     * @param int $id
     * @return Transaction|null
     */
    public function getTransactionById($id): ?Transaction
    {
        return $this->repository->getById($id);
    }

    /**
     * Get transactions by name
     *
     * @param string $name
     * @return Collection
     */
    public function getTransactionsByName($name): Collection
    {
        return $this->repository->getByName($name);
    }

    /**
     * Get transactions by status
     *
     * @param string $status
     * @return Collection
     */
    public function getTransactionsByStatus($status): Collection
    {
        return $this->repository->getByStatus($status);
    }

    /**
     * Create new transaction
     *
     * @param array $data
     * @return Transaction
     */
    public function createTransaction(array $data): Transaction
    {
        return $this->repository->create($data);
    }

    /**
     * Update transaction
     *
     * @param int $id
     * @param array $data
     * @return Transaction|null
     */
    public function updateTransaction($id, array $data): ?Transaction
    {
        return $this->repository->update($id, $data);
    }

    /**
     * Delete transaction
     *
     * @param int $id
     * @return bool
     */
    public function deleteTransaction($id): bool
    {
        return $this->repository->delete($id);
    }

    /**
     * Get transactions by student ID
     *
     * @param int $studentId
     * @return Collection
     */
    public function getTransactionsByStudentId($studentId): Collection
    {
        return Transaction::with(['bankAccount'])
            ->where('student_id', $studentId)
            ->latest()
            ->get();
    }

    /**
     * Get transactions by teacher ID
     *
     * @param int $teacherId
     * @return Collection
     */
    public function getTransactionsByTeacherId($teacherId): Collection
    {
        return Transaction::with(['bankAccount'])
            ->where('teacher_id', $teacherId)
            ->latest()
            ->get();
    }
}
