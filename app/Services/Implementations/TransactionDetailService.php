<?php

namespace App\Services;

use App\Repositories\TransactionDetailRepositoryInterface;

class TransactionDetailService implements TransactionDetailServiceInterface
{
    protected $repository;

    public function __construct(TransactionDetailRepositoryInterface $repository)
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
