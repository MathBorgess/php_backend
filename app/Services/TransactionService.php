<?php

namespace App\Services;

use App\Models\Transaction;
use App\Repositories\TransactionRepositoryInterface;

class TransactionService
{
    private $repository;

    public function __construct(TransactionRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAll($data)
    {
        return $this->repository->getAll(
            $data
        );
    }

    public function getById(string $id)
    {
        return $this->repository->findById($id);
    }
}
