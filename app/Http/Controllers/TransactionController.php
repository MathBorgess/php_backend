<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * @OA\Get(
     *     path="/transactions",
     *     tags={"Transactions"},
     *     summary="Get transactions with optional filters",
     *     description="Returns a list of transactions optionally filtered by transaction name, category name, category ID, and type. Includes the category details for each transaction.",
     *     @OA\Parameter(
     *         name="transaction_name",
     *         in="query",
     *         description="Name of the transaction",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="category_name",
     *         in="query",
     *         description="Name of the category",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="category_id",
     *         in="query",
     *         description="ID of the category",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="type",
     *         in="query",
     *         description="Type of the transaction (income or expense)",
     *         required=false,
     *         @OA\Schema(type="string", enum={"income", "expense"})
     *     ),
     *     @OA\Parameter(
     *         name="include_category",
     *         in="query",
     *         description="Include category details for each transaction (true or false)",
     *         required=false,
     *         @OA\Schema(type="boolean", default="false")
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
     *                 @OA\Property(property="type", type="string", enum={"income", "expense"}),
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $query = Transaction::query();

        if ($request->has('transaction_name')) {
            $query->where('name', 'like', '%' . $request->input('transaction_name') . '%');
        }

        if ($request->has('category_name')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->input('category_name') . '%');
            });
        }

        if ($request->has('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        if ($request->has('type')) {
            $query->where('type', $request->input('type'));
        }

        $query->with(['category' => function ($query) {
            $query->select('id','name');
        }]);

        $transactions = $query->get();
        return [
            "status" => 1,
            "data" => $transactions
        ];
    }
    /**
     * @OA\Get(
     *     path="/transactions/{id}",
     *     tags={"Users"},
     *     summary="Get a transaction by ID",
     *     description="Returns a transaction by its ID",
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
    public function show(string $id)
    {
        $transaction = Transaction::findOrFail($id)->with('category');
        return [
            "status" => 1,
            "data" => $transaction
        ];
    }
}
