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
    public function authorize(AuthorizeRequest $request): Response
    {
        $request->validate([
            'client_id' => 'required|string',
            'client_secret' => 'required|string',
            'grant_type' => ['required', Rule::in(['password', 'refresh_token'])],
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
        return $this->service->authorize($request);
    }
}
