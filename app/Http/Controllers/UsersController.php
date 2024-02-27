<?php

namespace App\Http\Controllers;

use App\Models\{TransactionCategory, Transaction, User};
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(10);
        return [
            "status" => 1,
            "data" => $users
        ];
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([
            'fullname' => 'required|string',
            'email' => 'required|email|unique:users',
            'cpf' => 'required|string|unique:users',
            'password' => 'required|string'
        ]);
        $user = User::create([
            'fullname' => $request->fullname,
            'email' => $request->email,
            'cpf' => $request->cpf,
            'password' => password_hash($request->password, PASSWORD_BCRYPT)
        ]);
        return [
            'status' => 1,
            'data' => $user
        ];
    }

    public function show(User $user)
    {
        return [
            'status' => 1,
            'data' => $user
        ];
    }
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'fullname' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'cpf' => 'required|string|unique:users,cpf,' . $user->id,
            'password' => 'required|string'
        ]);
        $user->update([
            'fullname' => $request->fullname,
            'email' => $request->email,
            'cpf' => $request->cpf,
            'password' => password_hash($request->password, PASSWORD_BCRYPT)
        ]);
        return [
            'status' => 1,
            'data' => $user
        ];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return [
            'status' => 1,
            'data' => null
        ];
    }
    public function categories_index($user_id)
    {
        $user = User::findOrFail($user_id);
        $categories = TransactionCategory::where('user_id', $user->id)->latest()->paginate(10);
        return [
            'status' => 1,
            'data' => $categories
        ];
    }
    public function categories_store($user_id, Request $request)
    {
        $request->validate([
            'name' => 'required|string'
        ]);
        $user = User::findOrFail($user_id);
        $category = TransactionCategory::create([
            'name' => $request->name,
            'user_id' => $user_id
        ]);
        $category->user()->associate($user);
        $category->save();
        return [
            'status' => 1,
            'data' => $category
        ];
    }
    public function categories_update($user_id, $id, Request $request)
    {
        $transactionCartegory = TransactionCategory::findOrFail($id);
        $request->validate([
            'name' => 'required|string',
            'user_id' => 'required|exists:users,id'
        ]);
        $transactionCartegory->update([
            'name' => $request->name,
            'user_id' => $request->user_id
        ]);
        return [
            'status' => 1,
            'data' => $transactionCartegory
        ];
    }
    public function categories_destroy($user_id, $id)
    {
        $transactionCartegory = TransactionCategory::findOrFail($id);
        $transactionCartegory->delete();
        return [
            'status' => 1,
            'data' => null
        ];
    }
    public function transactions_index($user_id, $id)
    {
        $transactions = Transaction::where('category_id', $id)->latest()->paginate(10);
        return [
            'status' => 1,
            'data' => $transactions
        ];
    }
    public function transactions_show($user_id, $category_id, $id)
    {
        $transaction = TransactionCategory::findOrFail($id);
        return [
            'status' => 1,
            'data' => $transaction
        ];
    }
    public function transactions_store(string $user_id, string $category_id, Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'value' => 'required|numeric',
            'type' => Rule::in(Transaction::transactionTypes())
        ]);

        $category = TransactionCategory::findOrFail($category_id);
        $transaction = Transaction::create([
            'name' => $request->name,
            'value' => $request->value,
            'category_id' => $category->id,
            'type' => $request->type
        ]);
        $transaction->category()->associate($category);
        $transaction->save();
        return [
            'status' => 1,
            'data' => $transaction
        ];
    }
    public function transactions_update($user_id, $category_id, $id, Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'value' => 'required|numeric',
            'type' => Rule::in(Transaction::transactionTypes()),
            'category_id' => 'required|exists:transaction_categories,id'
        ]);
        $transaction = Transaction::findOrFail($id);
        $transaction->update([
            'name' => $request->name,
            'value' => $request->value,
            'category_id' => $request->category_id,
            'type' => $request->type
        ]);
        return [
            'status' => 1,
            'data' => $transaction
        ];
    }
    public function transactions_destroy($user_id, $category_id, $id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->delete();
        return [
            'status' => 1,
            'data' => null
        ];
    }
}
