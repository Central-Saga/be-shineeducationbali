<?php

namespace App\Repositories\Eloquent;

use App\Models\TransactionDetail;
use App\Repositories\Contracts\TransactionDetailRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class TransactionDetailRepository implements TransactionDetailRepositoryInterface
{
    protected $model;

    /**
     * TransactionDetailRepository constructor.
     *
     * @param TransactionDetail $model
     */
    public function __construct(TransactionDetail $model)
    {
        $this->model = $model;
    }

    /**
     * Get all transaction details
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return $this->model->all();
    }

    /**
     * Get transaction detail by id
     *
     * @param int $id
     * @return TransactionDetail|null
     */
    public function getById($id): ?TransactionDetail
    {
        return $this->model->find($id) instanceof TransactionDetail 
            ? $this->model->find($id) 
            : null;
    }

    /**
     * Get transaction detail by name
     *
     * @param string $name
     * @return Collection
     */
    public function getByName($name): Collection
    {
        return $this->model->where('name', 'like', "%{$name}%")->get();
    }

    /**
     * Create transaction detail
     *
     * @param array $data
     * @return TransactionDetail
     */
    public function create(array $data): TransactionDetail
    {
        return $this->model->create($data);
    }

    /**
     * Update transaction detail
     *
     * @param int $id
     * @param array $data
     * @return TransactionDetail|null
     */
    public function update($id, array $data): ?TransactionDetail
    {
        $transactionDetail = $this->getById($id);
        
        if (!$transactionDetail) {
            return null;
        }
        
        $transactionDetail->update($data);
        
        return $transactionDetail;
    }

    /**
     * Delete transaction detail
     *
     * @param int $id
     * @return bool
     */
    public function delete($id): bool
    {
        $transactionDetail = $this->getById($id);
        
        if (!$transactionDetail) {
            return false;
        }
        
        return $transactionDetail->delete();
    }
}
