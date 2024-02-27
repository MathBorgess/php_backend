<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('users', UsersController::class);

Route::get('users/{user_id}/categories', [UsersController::class, 'categories_index']);
Route::post('users/{user_id}/categories', [UsersController::class, 'categories_store']);
Route::put('users/{user_id}/categories/{id}', [UsersController::class, 'categories_update']);
Route::delete('users/{user_id}/categories/{id}', [UsersController::class, 'categories_destroy']);

Route::get('users/{user_id}/categories/{category_id}/transactions', [UsersController::class, 'transactions_index']);
Route::get('users/{user_id}/categories/{category_id}/transactions/{id}', [UsersController::class, 'transactions_show']);
Route::post('users/{user_id}/categories/{category_id}/transactions', [UsersController::class, 'transactions_store']);
Route::put('users/{user_id}/categories/{category_id}/transactions/{id}', [UsersController::class, 'transactions_update']);
Route::delete('users/{user_id}/categories/{category_id}/transactions/{id}', [UsersController::class, 'transactions_destroy']);

