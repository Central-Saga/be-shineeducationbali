<?php

namespace App\Repositories;

use App\Models\BankAccount;

class BankAccountRepository implements BankAccountRepositoryInterface
{
    protected $model;

    public function __construct(BankAccount $model)
    {
        $this->model = $model;
    }

    public function getAll()
    {
        return $this->model->latest()->get();
    }

    public function getById($id)
    {
        return $this->model->findOrFail($id);
    }

    public function getByName($name)
    {
        return $this->model->where('bank_name', 'like', "%{$name}%")
            ->orWhere('account_holder', 'like', "%{$name}%")
            ->latest()
            ->get();
    }

    public function getByStatus($status)
    {
        return $this->model->where('status', $status)->latest()->get();
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $bankAccount = $this->model->findOrFail($id);
        $bankAccount->update($data);
        return $bankAccount;
    }

    public function delete($id)
    {
        $bankAccount = $this->model->findOrFail($id);
        return $bankAccount->delete();
    }
}
