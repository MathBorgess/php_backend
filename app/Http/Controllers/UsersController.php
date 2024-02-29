<?php

namespace App\Http\Controllers;

use Illuminate\Validation\Rule;
use Illuminate\Http\{Request, Response};
use App\Http\Requests\Users\{
    StoreUserRequest,
    UpdateUserRequest,
    UpsertCategoriesRequest,
    StoreTransactionRequest
};
use App\Services\UserService;

class UsersController extends Controller
{
    private $service;
    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    /**
     * @OA\Get(
     *     path="/users",
     *    tags={"Users"},
     *    summary="List all users",
     *   @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Include additional information (e.g., profile)",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     description="Returns a list of all users",
     *  @OA\Response(
     *         response=200,
     *         description="Users retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="string"),
     *             @OA\Property(property="fullname", type="string"),
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="cpf", type="string"),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time")
     *         )
     *     )
     *   )
     * @return User[]
     */
    public function index()
    {
        $users = $this->service->getAll();
        return response()->json($users, 200);
    }
    /**
     * @OA\Get(
     *     path="/users/{id}",
     *    tags={"Users"},
     *    summary="returns an user",
     *   @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="user id",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     description="Returns an user",
     *  @OA\Response(
     *         response=200,
     *         description="User retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="string"),
     *             @OA\Property(property="fullname", type="string"),
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="cpf", type="string"),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time")
     *         )
     *     )
     *   )
     *   )
     */
    public function show(string $id)
    {
        $user = $this->service->getById($id);
        return response()->json($user, 200);
    }

    /**
     * @OA\Post(
     *     path="/users",
     *     tags={"Users"},
     *     summary="Create a new user",
     *     description="Creates a new user with required parameters: email, CPF, password, and full name",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "cpf", "password", "fullname"},
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="cpf", type="string"),
     *             @OA\Property(property="password", type="string"),
     *             @OA\Property(property="fullname", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="string"),
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="cpf", type="string"),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time")
     *         )
     *     )
     * )
     * @return User
     */
    public function store(StoreUserRequest $request)
    {
        $request->validated();
        $user = $this->service->create($request);
        return response()->json($user, 201);
    }

    /**
     * @OA\Put(
     *     path="/users/{id}",
     *     tags={"Users"},
     *     summary="Update an user",
     *     description="Update an user with required parameters: email, CPF, and full name",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="user id",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "cpf", "fullname"},
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="cpf", type="string"),
     *             @OA\Property(property="fullname", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="string"),
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="cpf", type="string"),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time")
     *         )
     *     )
     * )
     * @return User
     */
    public function update(UpdateUserRequest $request, $id)
    {
        $request->validated();
        $user = $this->service->update($id, $request);
        return response()->json($user, 200);
    }

    /**
     * @OA\Delete(
     *     path="/users/{id}",
     *     tags={"Users"},
     *     summary="delete alll user data",
     *     description="delete an user, its categories and transactions",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="user id",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Deleted",
     *     )
     * )
     */
    public function destroy($id)
    {
        $this->service->delete($id);
        return response()->json(null, 204);
    }
    /**
     * @OA\Get(
     *     path="/users/{user_id}/categories",
     *     tags={"Users"},
     *     summary="Get all transaction categories of a user",
     *     description="Returns a list of transaction categories for the specified user.",
     *     @OA\Parameter(
     *         name="user_id",
     *         in="path",
     *         description="ID of the user",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="string"),
     *                 @OA\Property(property="name", type="string")
     *             )
     *         )
     *     )
     * )
     */
    public function categories_index(string $user_id)
    {
        $categories = $this->service->getCategories($user_id);
        return response()->json($categories, 200);
    }

    /**
    * @OA\Get(
    *     path="/users/{user_id}/categories/{id}",
    *     tags={"Users"},
    *     summary="Get a transaction category by ID",
    *     description="Returns a transaction category by its ID for the specified user.",
    *     @OA\Parameter(
    *         name="user_id",
    *         in="path",
    *         description="ID of the user",
    *         required=true,
    *         @OA\Schema(type="string")
    *     ),
    *     @OA\Parameter(
    *         name="id",
    *         in="path",
    *         description="ID of the category",
    *         required=true,
    *         @OA\Schema(type="string")
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Successful operation",
    *         @OA\JsonContent(
    *             type="object",
    *             @OA\Property(property="id", type="string"),
    *             @OA\Property(property="name", type="string")
    *         )
    *     ),
    *     @OA\Response(
    *         response=404,
    *         description="Category not found"
    *     )
    * )
    */
    public function categories_show(string $user_id, string $id)
    {
        $category = $this->service->getOneCategory($user_id, $id);
        return response()->json($category, 200);
    }

    /**
     * @OA\Post(
     *     path="/users/{user_id}/categories",
     *     tags={"Users"},
     *     summary="Create a new transaction category for a user",
     *     description="Creates a new transaction category for the specified user.",
     *     @OA\Parameter(
     *         name="user_id",
     *         in="path",
     *         description="ID of the user",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Category created successfully"
     *     )
     * )
     *
     */
    public function categories_store(string $user_id, UpsertCategoriesRequest $request)
    {
        $request->validated();
        $category = $this->service->createCategory($user_id, $request);
        return response()->json($category, 201);
    }
    /**
     * @OA\Put(
     *     path="/users/{user_id}/categories/{id}",
     *     tags={"Users"},
     *     summary="Update a transaction category of a user",
     *     description="Updates the name of the specified transaction category for the specified user.",
     *     @OA\Parameter(
     *         name="user_id",
     *         in="path",
     *         description="ID of the user",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the category",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category updated successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found"
     *     )
     * )
     */
    public function categories_update(string $user_id, string $id, UpsertCategoriesRequest $request)
    {
        $request->validated();
        $category = $this->service->updateCategory($user_id,$id, $request);
        return response()->json($category, 200);
    }
    /**
     * @OA\Delete(
     *     path="/users/{user_id}/categories/{id}",
     *     tags={"Users"},
     *     summary="Delete a transaction category of a user",
     *     description="Deletes the specified transaction category for the specified user.",
     *     @OA\Parameter(
     *         name="user_id",
     *         in="path",
     *         description="ID of the user",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the category",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Category deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found"
     *     )
     * )
     */
    public function categories_destroy(string $user_id, string $id)
    {
        $this->service->deleteCategory($user_id, $id);
        return response()->json(null, 204);
    }
    /**
    * @OA\Get(
    *     path="/users/{user_id}/categories/{category_id}/transactions",
    *     tags={"Users"},
    *     summary="Get all transactions of a category",
    *     description="Returns a list of transactions for the specified category and user.",
    *     @OA\Parameter(
    *         name="user_id",
    *         in="path",
    *         description="ID of the user",
    *         required=true,
    *         @OA\Schema(type="string")
    *     ),
    *     @OA\Parameter(
    *         name="category_id",
    *         in="path",
    *         description="ID of the category",
    *         required=true,
    *         @OA\Schema(type="string")
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Successful operation",
    *         @OA\JsonContent(
    *             type="array",
    *             @OA\Items(
    *                 type="object",
    *                 @OA\Property(property="id", type="integer"),
    *                 @OA\Property(property="name", type="string"),
    *                 @OA\Property(property="value", type="integer"),
    *                 @OA\Property(property="category_id", type="string"),
    *                 @OA\Property(property="type", type="string", enum={"income", "expense"})
    *             )
    *         )
    *     )
    * )
     */
    public function transactions_index(string $user_id, string $id)
    {
        $transactions = $this->service->getTransactions($id, $user_id);
        return response()->json($transactions, 200);
    }

    /**
     * @OA\Get(
     *     path="/users/{user_id}/categories/{category_id}/transactions/{id}",
     *     tags={"Users"},
     *     summary="Get a transaction by ID",
     *     description="Returns a transaction by its ID for the specified category and user.",
     *     @OA\Parameter(
     *         name="user_id",
     *         in="path",
     *         description="ID of the user",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="category_id",
     *         in="path",
     *         description="ID of the category",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the transaction",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="value", type="integer"),
     *             @OA\Property(property="category_id", type="string"),
     *             @OA\Property(property="type", type="string", enum={"income", "expense"})
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Transaction not found"
     *     )
     * )
     */
    public function transactions_show(string $user_id, string $category_id, string $id)
    {
        $transaction = $this->service->getOneTransaction($category_id, $user_id, $id);
        return response()->json($transaction, 200);
    }
    /**
    * @OA\Post(
    *     path="/users/{user_id}/categories/{category_id}/transactions",
    *     tags={"Users"},
    *     summary="Create a new transaction for a category",
    *     description="Creates a new transaction for the specified category and user.",
    *     @OA\Parameter(
    *         name="user_id",
    *         in="path",
    *         description="ID of the user",
    *         required=true,
    *         @OA\Schema(type="string")
    *     ),
    *     @OA\Parameter(
    *         name="category_id",
    *         in="path",
    *         description="ID of the category",
    *         required=true,
    *         @OA\Schema(type="string")
    *     ),
    *     @OA\RequestBody(
    *         required=true,
    *         @OA\JsonContent(
    *             required={"name", "value", "type"},
    *             @OA\Property(property="name", type="string"),
    *             @OA\Property(property="value", type="integer"),
    *             @OA\Property(property="type", type="string", enum={"income", "expense"})
    *         )
    *     ),
    *     @OA\Response(
    *         response=201,
    *         description="Transaction created successfully"
    *     )
    * )
     */
    public function transactions_store(string $user_id, string $category_id, StoreTransactionRequest $request)
    {
        $request->validated();
        $transaction = $this->service->createTransaction($category_id, $user_id, $request);
        return response()->json($transaction, 201);
    }
    /**
     * @OA\Put(
     *     path="/users/{user_id}/categories/{category_id}/transactions/{id}",
     *     tags={"Users"},
     *     summary="Update a transaction of a category",
     *     description="Updates a transaction for the specified category and user.",
     *     @OA\Parameter(
     *         name="user_id",
     *         in="path",
     *         description="ID of the user",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="category_id",
     *         in="path",
     *         description="ID of the category",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the transaction",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="value", type="integer"),
     *             @OA\Property(property="type", type="string", enum={"income", "expense"})
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Transaction updated successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Transaction not found"
     *     )
     * )
     */
    public function transactions_update(string $user_id, string $category_id, string $id, StoreTransactionRequest $request)
    {
        $request->validated();
        $transaction = $this->service->updateTransaction($category_id, $user_id, $id, $request);
        return response()->json($transaction, 200);
    }
    /**
     * @OA\Delete(
     *     path="/users/{user_id}/categories/{category_id}/transactions/{id}",
     *     tags={"Users"},
     *     summary="Delete a transaction of a category",
     *     description="Deletes a transaction for the specified category and user.",
     *     @OA\Parameter(
     *         name="user_id",
     *         in="path",
     *         description="ID of the user",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="category_id",
     *         in="path",
     *         description="ID of the category",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the transaction",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Transaction deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Transaction not found"
     *     )
     * )
    */
    public function transactions_destroy(string $user_id, string $category_id, string $id)
    {
        $this->service->deleteTransaction($category_id, $user_id, $id);
        return response()->json(null, 204);
    }
}
