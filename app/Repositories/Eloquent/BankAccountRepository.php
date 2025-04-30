<?php

namespace App\Repositories\Eloquent;

use App\Models\BankAccount;
use App\Repositories\Contracts\BankAccountRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class BankAccountRepository implements BankAccountRepositoryInterface
{
    protected $model;

    /**
     * BankAccountRepository constructor.
     *
     * @param BankAccount $model
     */
    public function __construct(BankAccount $model)
    {
        $this->model = $model;
    }

    /**
     * Get all bank accounts
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return $this->model->latest()->get();
    }

    /**
     * Get bank account by id
     *
     * @param int $id
     * @return BankAccount|null
     */
    public function getById($id): ?BankAccount
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Get bank account by name
     *
     * @param string $name
     * @return Collection
     */
    public function getByName($name): Collection
    {
        return $this->model->where('bank_name', 'like', "%{$name}%")
            ->orWhere('account_holder', 'like', "%{$name}%")
            ->latest()
            ->get();
    }

    /**
     * Get bank account by status
     *
     * @param string $status
     * @return Collection
     */
    public function getByStatus($status): Collection
    {
        return $this->model->where('status', $status)->latest()->get();
    }

    /**
     * Create bank account
     *
     * @param array $data
     * @return BankAccount
     */
    public function create(array $data): BankAccount
    {
        return $this->model->create($data);
    }

    /**
     * Update bank account
     *
     * @param int $id
     * @param array $data
     * @return BankAccount|null
     */
    public function update($id, array $data): ?BankAccount
    {
        $bankAccount = $this->getById($id);
        if (!$bankAccount) {
            return null;
        }
        
        $bankAccount->update($data);
        return $bankAccount;
    }

    /**
     * Delete bank account
     *
     * @param int $id
     * @return bool
     */
    public function delete($id): bool
    {
        $bankAccount = $this->getById($id);
        if (!$bankAccount) {
            return false;
        }
        
        return $bankAccount->delete();
    }
}
