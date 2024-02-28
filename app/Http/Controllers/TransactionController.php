<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Services\TransactionService;
use Illuminate\Http\{Request, Response};

class TransactionController extends Controller
{
    protected $service;
    public function __construct(TransactionService $service)
    {
        $this->service = $service;
    }

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
        $transactions = $this->service->getAll($request);
        return response()->json($transactions, 200);
    }
    /**
     * @OA\Get(
     *     path="/transactions/{id}",
     *     tags={"Transactions"},
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
        $transaction = $this->service->getById($id);
        return response()->json($transaction, 200);
    }
}
