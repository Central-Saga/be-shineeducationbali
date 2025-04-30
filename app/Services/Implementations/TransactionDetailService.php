<?php

namespace App\Services\Implementations;

use App\Models\TransactionDetail;
use App\Repositories\Contracts\TransactionDetailRepositoryInterface;
use App\Services\Contracts\TransactionDetailServiceInterface;
use Illuminate\Database\Eloquent\Collection;

class TransactionDetailService implements TransactionDetailServiceInterface
{
    protected $repository;

    /**
     * TransactionDetailService constructor.
     *
     * @param TransactionDetailRepositoryInterface $repository
     */
    public function __construct(TransactionDetailRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get all transaction details
     *
     * @return Collection
     */
    public function getAllTransactionDetails(): Collection
    {
        return $this->repository->getAll();
    }

    /**
     * Get transaction detail by ID
     *
     * @param int $id
     * @return TransactionDetail|null
     */
    public function getTransactionDetailById($id): ?TransactionDetail
    {
        return $this->repository->getById($id);
    }

    /**
     * Get transaction details by transaction ID
     *
     * @param int $transactionId
     * @return Collection
     */
    public function getTransactionDetailsByTransactionId($transactionId): Collection
    {
        return TransactionDetail::where('transaction_id', $transactionId)->get();
    }

    /**
     * Create new transaction detail
     *
     * @param array $data
     * @return TransactionDetail
     */
    public function createTransactionDetail(array $data): TransactionDetail
    {
        // Calculate amount if quantity and price are provided
        if (isset($data['quantity']) && isset($data['price']) && !isset($data['amount'])) {
            $data['amount'] = $data['quantity'] * $data['price'];
        }
        
        return $this->repository->create($data);
    }

    /**
     * Update transaction detail
     *
     * @param int $id
     * @param array $data
     * @return TransactionDetail|null
     */
    public function updateTransactionDetail($id, array $data): ?TransactionDetail
    {
        // Calculate amount if quantity and price are provided
        if (isset($data['quantity']) && isset($data['price']) && !isset($data['amount'])) {
            $data['amount'] = $data['quantity'] * $data['price'];
        }
        
        return $this->repository->update($id, $data);
    }

    /**
     * Delete transaction detail
     *
     * @param int $id
     * @return bool
     */
    public function deleteTransactionDetail($id): bool
    {
        return $this->repository->delete($id);
    }

    /**
     * Calculate subtotal for transaction detail
     *
     * @param int $id
     * @return float
     */
    public function calculateSubtotal($id): float
    {
        $detail = $this->repository->getById($id);
        
        if (!$detail) {
            return 0;
        }
        
        // Return amount directly as it already contains the calculated value
        // TransactionDetail model doesn't have quantity and price properties
        return $detail->amount;
    }

    /**
     * Calculate discount amount for transaction detail
     *
     * @param int $id
     * @param float $discountPercentage
     * @return float
     */
    public function calculateDiscount($id, $discountPercentage): float
    {
        $subtotal = $this->calculateSubtotal($id);
        return $subtotal * ($discountPercentage / 100);
    }
}
