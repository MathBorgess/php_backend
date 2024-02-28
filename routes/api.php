<?php

use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('users', UsersController::class);
Route::controller(UsersController::class)->group(function () {
    Route::post('/users/{id}/password', 'update_password');
    Route::get('/users/{user_id}/categories', 'categories_index');
    Route::get('/users/{user_id}/categories/{id}', 'categories_show');
    Route::post('/users/{user_id}/categories', 'categories_store');
    Route::put('/users/{user_id}/categories/{id}', 'categories_update');
    Route::delete('/users/{user_id}/categories/{id}', 'categories_destroy');
    Route::post('/users/{user_id}/categories/{category_id}/transactions', 'transactions_store');
    Route::get('/users/{user_id}/categories/{id}/transactions', 'transactions_index');
    Route::get('/users/{user_id}/categories/{category_id}/transactions/{id}', 'transactions_show');
    Route::put('/users/{user_id}/categories/{category_id}/transactions/{id}', 'transactions_update');
    Route::delete('/users/{user_id}/categories/{category_id}/transactions/{id}', 'transactions_destroy');
});
Route::resource('transactions', TransactionController::class);
