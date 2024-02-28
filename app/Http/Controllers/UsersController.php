<?php

namespace App\Http\Controllers;

use App\Models\{TransactionCategory, Transaction, User};
use Illuminate\Validation\Rule;
use Illuminate\Http\{Request, Response};

class UsersController extends Controller
{
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
     *             @OA\Property(property="users", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="string"),
     *                 @OA\Property(property="fullname", type="string"),
     *                 @OA\Property(property="email", type="string"),
     *                 @OA\Property(property="cpf", type="string"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             ))
     *         )
     *     )
     *   )
     * @return User[]
     */
    public function index()
    {
        $users = User::latest()->paginate(10);
        return response()->json(["users" => $users], 200);
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
        $user = User::findOrFail($id);
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
    public function store(Request $request)
    {
        $request->validate([
            'fullname' => 'required|string',
            'email' => 'required|email|unique:users',
            'cpf' => 'required|string|unique:users',
            'password' => 'required|string'
        ]);
        $user = User::create([
            'fullname' => $request->fullname,
            'email' => $request->email,
            'cpf' => $request->cpf,
            'password' => password_hash($request->password, PASSWORD_BCRYPT)
        ]);
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
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'fullname' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'cpf' => 'required|string|unique:users,cpf,' . $user->id
        ]);
        $user->update([
            'fullname' => $request->fullname,
            'email' => $request->email,
            'cpf' => $request->cpf
        ]);
        return response()->json($user, 200);
    }

    /**
     * @OA\Post(
     *     path="/users/{id}/password",
     *     tags={"Users"},
     *     summary="Update an user`s password",
     *     description="Update an user`s password with required parameters: password",
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
     *             required={"password"},
     *             @OA\Property(property="password", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="User`s password updated successfully",
     *     )
     * )
     * @return User
     */
    public function update_password(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'password' => 'required|string'
        ]);
        $user->update([
            'password' => password_hash($request->password, PASSWORD_BCRYPT)
        ]);
        return response()->json(null, 204);
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
        $user = User::findOrFail($id);
        $user->delete();
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
        $categories = TransactionCategory::where('user_id', $user_id)->latest()->paginate(10);
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
        $category = TransactionCategory::findOrFail($id);
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
    public function categories_store(string $user_id, Request $request)
    {
        $request->validate([
            'name' => 'required|string'
        ]);
        $user = User::findOrFail($user_id);
        $category = TransactionCategory::create([
            'name' => $request->name,
            'user_id' => $user_id
        ]);
        $category->user()->associate($user);
        $category->save();
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
    public function categories_update(string $user_id, string $id, Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'user_id' => 'required|exists:users,id'
        ]);
        $transactionCartegory = TransactionCategory::findOrFail($id);
        $transactionCartegory->update([
            'name' => $request->name,
            'user_id' => $request->user_id
        ]);
        return response()->json($transactionCartegory, 200);
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
        $transactionCartegory = TransactionCategory::findOrFail($id);
        $transactionCartegory->delete();
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
        $transactions = Transaction::where('category_id', $id)->latest()->paginate(10);
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
        $transaction = TransactionCategory::findOrFail($id);
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
    public function transactions_store(string $user_id, string $category_id, Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'value' => 'required|numeric',
            'type' => Rule::in(Transaction::transactionTypes())
        ]);

        $category = TransactionCategory::findOrFail($category_id);
        $transaction = Transaction::create([
            'name' => $request->name,
            'value' => $request->value,
            'category_id' => $category->id,
            'type' => $request->type
        ]);
        $transaction->category()->associate($category);
        $transaction->save();
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
    public function transactions_update(string $user_id, string $category_id, string $id, Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'value' => 'required|numeric',
            'type' => Rule::in(Transaction::transactionTypes()),
            'category_id' => 'required|exists:transaction_categories,id'
        ]);
        $transaction = Transaction::findOrFail($id);
        $transaction->update([
            'name' => $request->name,
            'value' => $request->value,
            'category_id' => $request->category_id,
            'type' => $request->type
        ]);
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
    public function transactions_destroy(string $user_id, string $category_id, $id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->delete();
        return response()->json(null, 204);
    }
}
