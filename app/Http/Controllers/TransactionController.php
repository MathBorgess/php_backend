<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::query();

        if ($request->has('transaction_name')) {
            $query->where('name', 'like', '%' . $request->input('transaction_name') . '%');
        }

        if ($request->has('category_name')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->input('category_name') . '%');
            });
        }

        if ($request->has('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        if ($request->has('type')) {
            $query->where('type', $request->input('type'));
        }

        $query->with(['category' => function ($query) {
            $query->select('id','name');
        }]);

        $transactions = $query->get();
        return [
            "status" => 1,
            "data" => $transactions
        ];
    }
    public function show(string $id)
    {
        $transaction = Transaction::findOrFail($id)->with('category');
        return [
            "status" => 1,
            "data" => $transaction
        ];
    }
}
