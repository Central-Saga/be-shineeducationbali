<?php

namespace App\Services;

interface TransactionServiceInterface
{
    public function getAll();
    public function getById($id);
    public function getByName($name);
    public function getByStatus($status);
    public function getTransactionsByStatusPaid();
    public function getTransactionsByStatusUnpaid();
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}
