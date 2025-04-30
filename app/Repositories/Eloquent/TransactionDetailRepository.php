<?php

namespace App\Repositories;

use App\Models\TransactionDetail;

class TransactionDetailRepository implements TransactionDetailRepositoryInterface
{
    protected $model;

    public function __construct(TransactionDetail $model)
    {
        $this->model = $model;
    }

    public function getAll()
    {
        return $this->model->with(['transaction', 'program', 'leave'])->latest()->get();
    }

    public function getById($id)
    {
        return $this->model->with(['transaction', 'program', 'leave'])->findOrFail($id);
    }

    public function getByName($name)
    {
        return $this->model->with(['transaction', 'program', 'leave'])
            ->where('desc', 'like', "%{$name}%")
            ->orWhereHas('program', function ($query) use ($name) {
                $query->where('name', 'like', "%{$name}%");
            })
            ->orWhereHas('leave', function ($query) use ($name) {
                $query->where('reason', 'like', "%{$name}%");
            })
            ->latest()
            ->get();
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $transactionDetail = $this->model->findOrFail($id);
        $transactionDetail->update($data);
        return $transactionDetail;
    }

    public function delete($id)
    {
        $transactionDetail = $this->model->findOrFail($id);
        return $transactionDetail->delete();
    }
}
