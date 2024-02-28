<?php

namespace App\Services;

use App\Models\{User, TransactionCategory, Transaction};

class UserService
{
    public function getAll()
    {
        return User::latest();
    }

    public function getById(string $id)
    {
        return User::findOrFail($id);
    }

    public function create($data)
    {
        return User::create([
            'fullname' => $data->fullname,
            'email' => $data->email,
            'cpf' => $data->cpf,
            'password' => password_hash($data->password, PASSWORD_BCRYPT)
        ]);;
    }
    public function update(string $id, $data)
    {
        $user = User::findOrFail($id);
        $user->update([
            'fullname' => $request->fullname,
            'email' => $request->email,
            'cpf' => $request->cpf
        ]);
        return $user;
    }
    public function update_password(string $id, string $password)
    {
        $user = User::findOrFail($id);
        $user->update([
            'password' => password_hash($password, PASSWORD_BCRYPT)
        ]);
        return $user;
    }

    public function delete(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
    }

    public function getCategories(string $id)
    {
        $user = User::findOrFail($id);
        return $user->categories;
    }

    public function getOneCategory(string $id, string $category_id)
    {
        $user = User::findOrFail($id);
        return $user->categories->findOrFail($category_id);
    }

    public function createCategory(string $id, $data)
    {
        $user = User::findOrFail($id);
        $category = TransactionCategory::create([
            'name' => $data->name,
            'user_id' => $id
        ]);
        $category->user()->associate($user);
        $category->save();
        return $category;
    }

    public function updateCategory(string $id, string $category_id, $data)
    {
        $user = User::findOrFail($id);
        $query = TransactionCategory::query();
        $category = $query->where('user_id', $id)->findOrFail($category_id);
        $category->update([
            'name' => $data->name
        ]);
        return $category;
    }

    public function deleteCategory(string $id, string $category_id)
    {
        $query = TransactionCategory::query();
        $category = $query->where('user_id', $id)->findOrFail($category_id);
        $category->delete();
    }

    public function getTransactions(string $category_id,string $id)
    {
        $query = Transaction::query();
        $query->where('user_id', $id)->where('category_id', $category_id);
        return $query->get();
    }

    public function getOneTransaction(string $category_id, string $id, string $transaction_id)
    {
        $query = Transaction::query();
        $query->where('user_id', $id)->where('category_id', $category_id);
        return $query->findOrFail($transaction_id);
    }

    public function createTransaction(string $category_id, string $id, $data)
    {
        $user = User::findOrFail($id);
        $category = TransactionCategory::findOrFail($category_id);
        $transaction = Transaction::create([
            'name' => $data->name,
            'value' => $data->value,
            'category_id' => $category_id,
            'type' => $data->type,
            'user_id' => $id
        ]);
        $transaction->user()->associate($user);
        $transaction->category()->associate($category);
        $transaction->save();
        return $transaction;
    }

    public function updateTransaction(string $category_id, string $id, string $transaction_id, $data)
    {
        $query = Transaction::query();
        $transaction = $query->where('user_id', $id)->where('category_id', $category_id)->findOrFail($transaction_id);
        $transaction->update([
            'name' => $data->name,
            'value' => $data->value,
            'type' => $data->type
        ]);
        return $transaction;
    }

    public function deleteTransaction(string $category_id, string $id, string $transaction_id)
    {
        $query = Transaction::query();
        $transaction = $query->where('user_id', $id)->where('category_id', $category_id)->findOrFail($transaction_id);
        $transaction->delete();
    }
}
