<?php

namespace App\Repositories\Contracts;

use App\Models\TransactionDetail;
use Illuminate\Database\Eloquent\Collection;

interface TransactionDetailRepositoryInterface
{
    /**
     * Get all transaction details
     *
     * @return Collection
     */
    public function getAll(): Collection;

    /**
     * Get transaction detail by id
     *
     * @param int $id
     * @return TransactionDetail|null
     */
    public function getById($id): ?TransactionDetail;

    /**
     * Get transaction detail by name
     *
     * @param string $name
     * @return Collection
     */
    public function getByName($name): Collection;

    /**
     * Create transaction detail
     *
     * @param array $data
     * @return TransactionDetail
     */
    public function create(array $data): TransactionDetail;

    /**
     * Update transaction detail
     *
     * @param int $id
     * @param array $data
     * @return TransactionDetail|null
     */
    public function update($id, array $data): ?TransactionDetail;

    /**
     * Delete transaction detail
     *
     * @param int $id
     * @return bool
     */
    public function delete($id): bool;
}
