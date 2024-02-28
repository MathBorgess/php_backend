<?php

namespace App\Repositories;

interface UserRepositoryInterface
{
    public function getAll($data): array;
    public function findById(string $id): object|null;
    public function create(array $data): object;
    public function update(string $id, array $data): object|null;
    public function delete(string $id): bool;

    public function findByEmail(string $email): object|null;

    public function getAllCategories(string $id): array;
    public function getCategory(string $id, string $category_id): array;
    public function createCategory(string $id, $data): object|null;
    public function updateCategory(string $id, string $category_id, $data): object;
    public function deleteCategory(string $id, string $category_id): bool;

    public function getAllTransactions(string $id, string $category_id): array;
    public function getTransaction(string $id, string $category_id, string $transaction_id): object|null;
    public function createTransaction(string $id, string $category_id, $data): object|null;
    public function updateTransaction(string $id, string $category_id, string $transaction_id): object;
    public function deleteTransaction(string $id, string $category_id, string $transaction_id): bool;
}
