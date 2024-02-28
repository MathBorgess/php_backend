<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\{UserRepositoryInterface, TransactionRepositoryInterface};
use App\Repositories\Eloquent\{UserRepository, TransactionRepository};

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(TransactionRepositoryInterface::class, TransactionRepository::class);
    }

    public function boot(): void
    {
        //
    }
}
