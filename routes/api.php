<?php

use App\Http\Controllers\{TransactionController, AuthController, UsersController};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(UsersController::class)->middleware('jwt')->group(function () {
    Route::get('/users/categories', 'categories_index');
    Route::post('/users/categories', 'categories_store');
    Route::get('/users/categories/{id}', 'categories_show');
    Route::put('/users/categories/{id}', 'categories_update');
    Route::delete('/users/categories/{id}', 'categories_destroy');
    Route::post('/users/categories/{category_id}/transactions', 'transactions_store');
    Route::get('/users/categories/{id}/transactions', 'transactions_index');
    Route::get('/users/categories/{category_id}/transactions/{id}', 'transactions_show');
    Route::put('/users/categories/{category_id}/transactions/{id}', 'transactions_update');
    Route::delete('/users/categories/{category_id}/transactions/{id}', 'transactions_destroy');
});
Route::resource('users', UsersController::class);
Route::resource('transactions', TransactionController::class);
Route::controller(AuthController::class)->group(function () {
    Route::post('auth', 'login');
    Route::middleware('jwt')->post('/auth/password', 'update_credentials');
});

Route::fallback(function () {
    return Response::json(["error" => 0], 404);
});
