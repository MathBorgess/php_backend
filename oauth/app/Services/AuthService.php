<?php

namespace App\Services;

use App\Repositories\ClientRepositoryInterface;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Requests\Auth\{AuthorizeRequest, TokenRequest};


class AuthService
{
    private $repository;

    public function __construct(ClientRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function authorize(AuthorizeRequest $request): Response
    {
        if ($request->response_type === 'code')
        {
            $client = $this->repository->getClientById($request->client_id);
            if ($client->redirect_uri === $request->redirect_uri)
            {

                $code = $this->repository->createAuthorizationCode($request->client_id, $request->nonce, $request->state);
                return response()->json(['code' => $code, 'state' => $request->state]);
            }
        }
        return response()->json(['error' => 'invalid_request'], 400);
    }

}
