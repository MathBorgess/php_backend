<?php

namespace App\Services;

use App\Models\{User, TransactionCategory, Transaction};
use App\Repositories\UserRepositoryInterface;

class UserService
{
    private $repository;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAll($data)
    {
        return $this->repository->getAll($data);
    }

    public function getById(string $id)
    {
        return $this->repository->findById($id);
    }

    public function create($data)
    {
        return $this->repository->create([
            'fullname' => $data->fullname,
            'email' => $data->email,
            'cpf' => $data->cpf,
            'password' => password_hash($data->password, PASSWORD_BCRYPT)
        ]);
    }
    public function update(string $id, $data)
    {
        return $this->repository->update($id, [
            'fullname' => $request->fullname,
            'email' => $request->email,
            'cpf' => $request->cpf
        ]);
    }

    public function delete(string $id)
    {
        return $this->repository->delete($id);
    }

    public function getCategories(string $id)
    {
        return $this->repository->getAllCategories($id);
    }

    public function getOneCategory(string $id, string $category_id)
    {
        return $this->repository->getCategory($id, $category_id);
    }

    public function createCategory(string $id, $data)
    {
        return $this->repository->createCategory($id, [
            'name' => $data->name,
            'user_id' => $id
        ]);
    }

    public function updateCategory(string $id, string $category_id, $data)
    {
        return $this->repository->updateCategory($id, $category_id, [
            'name' => $data->name
        ]);
    }

    public function deleteCategory(string $id, string $category_id)
    {
        return $this->repository->deleteCategory($id, $category_id);
    }

    public function getTransactions(string $category_id,string $id)
    {
        return $this->repository->getAllTransactions($id, $category_id);
    }

    public function getOneTransaction(string $category_id, string $id, string $transaction_id)
    {
        return $this->repository->getTransaction($id, $category_id, $transaction_id);
    }

    public function createTransaction(string $category_id, string $id, $data)
    {
        return $this->repository->createTransaction($id, $category_id, [
            'name' => $data->name,
            'value' => $data->value,
            'category_id' => $category_id,
            'type' => $data->type,
            'user_id' => $id
        ]);
    }

    public function updateTransaction(string $category_id, string $id, string $transaction_id, $data)
    {
        return $this->repository->updateTransaction($id, $category_id, $transaction_id, [
            'name' => $data->name,
            'value' => $data->value,
            'type' => $data->type
        ]);
    }

    public function deleteTransaction(string $category_id, string $id, string $transaction_id)
    {
        return $this->repository->deleteTransaction($id, $category_id, $transaction_id);
    }
}
