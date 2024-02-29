<?php

namespace App\Repositories\Eloquent;

use App\Models\User as Model;
use App\Repositories\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    private $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function getAll($data): array
    {
        return $this->model::latest()->get()->toArray();
    }

    public function findById(string $id): object|null
    {
        return $this->model->find($id);
    }

    public function create(array $data): object
    {
        return $this->model->create($data);
    }

    public function update(string $id, array $data): object|null
    {
        if (!$user = $this->findById($id)) {
            return null;
        }

        $user->update($data);

        return $user;
    }

    public function delete(string $id): bool
    {
        if (!$user = $this->findById($id))
            return false;

        return $user->delete();
    }


    public function getAllCategories(string $id): array
    {
        $user = $this->findById($id);
        return $user->categories()->get()->toArray();
    }

    public function getCategory(string $id, string $category_id): array
    {
        $user = $this->findById($id);
        return $user->categories->find($category_id)->get()->toArray();
    }

    public function createCategory(string $id, $data): object|null
    {
        $user = $this->findById($id);
        return $user->categories()->create($data);
    }

    public function updateCategory(string $id, string $category_id, $data): object
    {
        $user = $this->findById($id);
        return $user->categories()->find($category_id)->update($data);
    }

    public function deleteCategory(string $id, string $category_id): bool
    {
        $user = $this->findById($id);
        return $user->categories()->find($category_id)->delete();
    }

    public function getAllTransactions(string $id, string $category_id): array
    {
        $user = $this->findById($id);
        return $user->categories()->find($category_id)->transactions()->get()->toArray();
    }

    public function getTransaction(string $id, string $category_id, string $transaction_id): object|null
    {
        $user = $this->findById($id);
        return $user->categories()->find($category_id)->transactions()->find($transaction_id);
    }

    public function createTransaction(string $id, string $category_id, $data): object|null
    {
        $user = $this->findById($id);
        return $user->categories()->find($category_id)->transactions()->create($data);
    }

    public function updateTransaction(string $id, string $category_id, string $transaction_id): object
    {
        $user = $this->findById($id);
        return $user->categories()->find($category_id)->transactions()->find($transaction_id)->update($data);
    }

    public function deleteTransaction(string $id, string $category_id, string $transaction_id): bool
    {
        $user = $this->findById($id);
        return $user->categories()->find($category_id)->transactions()->find($transaction_id)->delete();
    }

    public function findByEmail(string $email): object|null
    {
        return $this->model->where('email', $email)->first();
    }
}
