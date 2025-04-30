<?php

namespace App\Services;

interface BankAccountServiceInterface
{
    public function getAll();
    public function getById($id);
    public function getByName($name);
    public function getByStatus($status);
    public function getBankAccountsByStatusAktif();
    public function getBankAccountsByStatusNonAktif();
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}
