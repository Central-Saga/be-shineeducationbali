<?php

namespace App\Repositories;

use App\Models\Transaction;

class TransactionRepository implements TransactionRepositoryInterface
{
    protected $model;

    public function __construct(Transaction $model)
    {
        $this->model = $model;
    }

    public function getAll()
    {
        return $this->model->with(['student', 'teacher', 'bankAccount'])->latest()->get();
    }

    public function getById($id)
    {
        return $this->model->with(['student', 'teacher', 'bankAccount'])->findOrFail($id);
    }

    public function getByName($name)
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

    public function getByStatus($status)
    {
        return $this->model->with(['student', 'teacher', 'bankAccount'])
            ->where('status', $status)
            ->latest()
            ->get();
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $transaction = $this->model->findOrFail($id);
        $transaction->update($data);
        return $transaction;
    }

    public function delete($id)
    {
        $transaction = $this->model->findOrFail($id);
        return $transaction->delete();
    }
}
