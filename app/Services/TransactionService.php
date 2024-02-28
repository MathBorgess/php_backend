<?php

namespace App\Services;

use App\Models\Transaction;

class TransactionService
{
    public function getAll($data)
    {
        $query = Transaction::query();

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

        return $query->get();
    }

    public function getById(string $id)
    {
        return Transaction::findOrFail($id)->with('category');
    }
}
