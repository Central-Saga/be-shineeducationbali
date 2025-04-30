<?php

namespace App\Repositories;

interface BankAccountRepositoryInterface
{
    public function getAll();
    public function getById($id);
    public function getByName($name);
    public function getByStatus($status);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}
