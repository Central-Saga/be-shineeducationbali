<?php

namespace App\Services\Implementations;

use App\Models\BankAccount;
use App\Repositories\Contracts\BankAccountRepositoryInterface;
use App\Services\Contracts\BankAccountServiceInterface;
use Illuminate\Database\Eloquent\Collection;

class BankAccountService implements BankAccountServiceInterface
{
    protected $repository;

    /**
     * BankAccountService constructor.
     *
     * @param BankAccountRepositoryInterface $repository
     */
    public function __construct(BankAccountRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get all bank accounts
     *
     * @return Collection
     */
    public function getAllBankAccounts(): Collection
    {
        return $this->repository->getAll();
    }

    /**
     * Get bank account by ID
     *
     * @param int $id
     * @return BankAccount|null
     */
    public function getBankAccountById($id): ?BankAccount
    {
        return $this->repository->getById($id);
    }

    /**
     * Get bank account by name
     *
     * @param string $name
     * @return Collection
     */
    public function getBankAccountByName($name): Collection
    {
        return $this->repository->getByName($name);
    }

    /**
     * Get bank accounts by status
     *
     * @param string $status
     * @return Collection
     */
    public function getBankAccountsByStatus($status): Collection
    {
        return $this->repository->getByStatus($status);
    }

    /**
     * Create new bank account
     *
     * @param array $data
     * @return BankAccount
     */
    public function createBankAccount(array $data): BankAccount
    {
        return $this->repository->create($data);
    }

    /**
     * Update bank account
     *
     * @param int $id
     * @param array $data
     * @return BankAccount|null
     */
    public function updateBankAccount($id, array $data): ?BankAccount
    {
        return $this->repository->update($id, $data);
    }

    /**
     * Delete bank account
     *
     * @param int $id
     * @return bool
     */
    public function deleteBankAccount($id): bool
    {
        return $this->repository->delete($id);
    }
}
