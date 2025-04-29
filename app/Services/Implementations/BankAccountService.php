<?php

namespace App\Services;

use App\Repositories\BankAccountRepositoryInterface;

class BankAccountService implements BankAccountServiceInterface
{
    protected $repository;

    public function __construct(BankAccountRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAll()
    {
        return $this->repository->getAll();
    }

    public function getById($id)
    {
        return $this->repository->getById($id);
    }

    public function getByName($name)
    {
        return $this->repository->getByName($name);
    }

    public function getByStatus($status)
    {
        if (!in_array($status, ['Aktif', 'Non Aktif'])) {
            throw new \InvalidArgumentException("Invalid status value: $status");
        }

        return $this->repository->getByStatus($status);
    }

    public function getBankAccountsByStatusAktif()
    {
        return $this->repository->getByStatus('Aktif');
    }

    public function getBankAccountsByStatusNonAktif()
    {
        return $this->repository->getByStatus('Non Aktif');
    }

    public function create(array $data)
    {
        return $this->repository->create($data);
    }

    public function update($id, array $data)
    {
        return $this->repository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->repository->delete($id);
    }
}
