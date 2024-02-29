<?php

namespace App\Http\Controllers;

use Illuminate\Validation\Rule;
use Illuminate\Http\{Request, Response};
use App\Http\Requests\Auth\{LoginRequest, UpdateCredentialsRequest};
use App\Services\AuthService;

class AuthController extends Controller
{
    private $service;
    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    /**
     * @OA\Post(
     *    path="/auth",
     *    tags={"Auth"},
     *    description="Returns an user token if the credentials are valid",
     *    @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="password", type="string")
     *         )
     *     ),
     *  @OA\Response(
     *         response=201,
     *         description="User'token retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="token", type="string"),
     *         )
     *     )
     *   )
     * @return string
     */
    public function login(LoginRequest $request)
    {
        $request->validated();
        $token = $this->service->login($request->email, $request->password);
        return response()->json(["token"=>$token], 201);
    }
    /**
     * @OA\Post(
     *     path="/auth/{id}/password",
     *    tags={"Auth"},
     *     description="Returns user id if the credentials are valid",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"password"},
     *             @OA\Property(property="password", type="string")
     *         )
     *     ),
     *  @OA\Response(
     *         response=201,
     *         description="User retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="string"),
     *         )
     *     )
     *   )
     */
    public function update_password(UpdateCredentialsRequest $request)
    {
        $request->validate();
        $this->service->update_credentials($request->user->id, $request->password);
        return response()->json(null, 204);
    }
}
