<?php

namespace App\Repositories\Eloquent;

use App\Models\Transaction;
use App\Repositories\Contracts\TransactionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class TransactionRepository implements TransactionRepositoryInterface
{
    protected $model;

    /**
     * TransactionRepository constructor.
     *
     * @param Transaction $model
     */
    public function __construct(Transaction $model)
    {
        $this->model = $model;
    }

    /**
     * Get all transactions
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return $this->model->with(['student', 'teacher', 'bankAccount'])->latest()->get();
    }

    /**
     * Get transaction by id
     *
     * @param int $id
     * @return Transaction|null
     */
    public function getById($id): ?Transaction
    {
        $transaction = $this->model->find($id);
        return $transaction ? $transaction->load(['student', 'teacher', 'bankAccount']) : null;
    }

    /**
     * Get transaction by name
     *
     * @param string $name
     * @return Collection
     */
    public function getByName($name): Collection
    {
        return $this->model->with(['student', 'teacher', 'bankAccount'])
            ->whereHas('student', function ($query) use ($name) {
                $query->where('name', 'like', "%{$name}%");
            })
            ->orWhereHas('teacher', function ($query) use ($name) {
                $query->where('name', 'like', "%{$name}%");
            })
            ->latest()
            ->get();
    }

    /**
     * Get transaction by status
     *
     * @param string $status
     * @return Collection
     */
    public function getByStatus($status): Collection
    {
        return $this->model->with(['student', 'teacher', 'bankAccount'])
            ->where('status', $status)
            ->latest()
            ->get();
    }

    /**
     * Create transaction
     *
     * @param array $data
     * @return Transaction
     */
    public function create(array $data): Transaction
    {
        return $this->model->create($data);
    }

    /**
     * Update transaction
     *
     * @param int $id
     * @param array $data
     * @return Transaction|null
     */
    public function update($id, array $data): ?Transaction
    {
        $transaction = $this->getById($id);
        if (!$transaction) {
            return null;
        }
        $transaction->update($data);
        return $transaction;
    }

    /**
     * Delete transaction
     *
     * @param int $id
     * @return bool
     */
    public function delete($id): bool
    {
        $transaction = $this->getById($id);
        if (!$transaction) {
            return false;
        }
        return $transaction->delete();
    }
}
