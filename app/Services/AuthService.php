<?php

namespace App\Services;

use App\Repositories\UserRepositoryInterface;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
    private $repository;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function login (string $email, string $password): string
    {
        $user = $this->repository->findByEmail($email);
        if ($user && password_verify($password, $user->password)) {
            $token = JWTAuth::fromUser($user);
            return $token;
        }
        throw new Exception('Invalid credentials');
    }

    public function update_credentials(string $id, string $password)
    {
        return $this->repository->update($id, [
            'password' => password_hash($password, PASSWORD_BCRYPT)
        ]);
    }

}
