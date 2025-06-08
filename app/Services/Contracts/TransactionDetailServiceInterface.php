<?php

namespace App\Services\Contracts;

use App\Models\TransactionDetail;
use Illuminate\Database\Eloquent\Collection;

interface TransactionDetailServiceInterface
{
    /**
     * Get all transaction details
     *
     * @return Collection
     */
    public function getAllTransactionDetails();

    /**
     * Get transaction detail by ID
     *
     * @param int $id
     * @return TransactionDetail|null
     */
    public function getTransactionDetailById($id);

    /**
     * Get transaction details by transaction ID
     *
     * @param int $transactionId
     * @return Collection
     */
    public function getTransactionDetailsByTransactionId($transactionId);

    /**
     * Create new transaction detail
     *
     * @param array $data
     * @return TransactionDetail
     */
    public function createTransactionDetail(array $data);

    /**
     * Update transaction detail
     *
     * @param int $id
     * @param array $data
     * @return TransactionDetail|null
     */
    public function updateTransactionDetail($id, array $data);

    /**
     * Delete transaction detail
     *
     * @param int $id
     * @return bool
     */
    public function deleteTransactionDetail($id);

    /**
     * Calculate subtotal for transaction detail
     *
     * @param int $id
     * @return float
     */
    public function calculateSubtotal($id);

    /**
     * Calculate discount amount for transaction detail
     *
     * @param int $id
     * @param float $discountPercentage
     * @return float
     */
    public function calculateDiscount($id, $discountPercentage);
}
