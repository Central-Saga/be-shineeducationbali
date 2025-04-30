<?php

namespace App\Services;

interface TransactionDetailServiceInterface
{
    public function getAll();
    public function getById($id);
    public function getByName($name);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}
