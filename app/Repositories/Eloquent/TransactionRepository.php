<?php

namespace App\Repositories\Eloquent;

use App\Models\Transaction as Model;
use App\Repositories\TransactionRepositoryInterface;

class TransactionRepository implements TransactionRepositoryInterface
{
    private $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function getAll($data): array
    {
        $query = $this->model::query();

        if ($data->has('transaction_name')) {
            $query->where('name', 'like', '%' . $data->input('transaction_name') . '%');
        }

        if ($data->has('category_name')) {
            $query->whereHas('category', function ($q) use ($data) {
                $q->where('name', 'like', '%' . $data->input('category_name') . '%');
            });
        }

        if ($data->has('category_id')) {
            $query->where('category_id', $data->input('category_id'));
        }

        if ($data->has('type')) {
            $query->where('type', $data->input('type'));
        }

        if ($data->has('include_category') && $data->input('include_category') === 'true') {
            $query->with(['category' => function ($query) {
                $query->select('id','name');
            }]);
        }
        return $query->get()->toArray();
    }

    public function findById(string $id): object|null
    {
        return $this->model->find($id)->with('category');
    }

    public function create(array $data): object
    {
        return $this->model->create($data);
    }

    public function update(string $id, array $data): object|null
    {
        if (!$transaction = $this->findById($id)) {
            return null;
        }

        $transaction->update($data);

        return $transaction;
    }

    public function delete(string $id): bool
    {
        if (!$transaction = $this->findById($id))
            return false;

        return $transaction->delete();
    }

}
