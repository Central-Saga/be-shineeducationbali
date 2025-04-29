<?php

namespace App\Services;

use App\Repositories\TransactionRepositoryInterface;

class TransactionService implements TransactionServiceInterface
{
    protected $repository;

    public function __construct(TransactionRepositoryInterface $repository)
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
        if (!in_array($status, ['paid', 'unpaid'])) {
            throw new \InvalidArgumentException("Invalid status value: $status");
        }

        return $this->repository->getByStatus($status);
    }

    public function getTransactionsByStatusPaid()
    {
        return $this->repository->getByStatus('paid');
    }

    public function getTransactionsByStatusUnpaid()
    {
        return $this->repository->getByStatus('unpaid');
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
