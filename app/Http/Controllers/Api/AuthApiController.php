<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Repositories\UserRepositoryInterface;

class AuthApiController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function apiLogin(LoginRequest $request)
    {
        $credentials = $request->only('code', 'password');
        $user = $this->userRepository->attemptApiLogin($credentials);
        if ($user) {
            $token = $user->createToken('api_token')->plainTextToken;

            return response()->json([
                'token' => $token,
                'data' => new UserResource($user),
            ]);
        }

        return response()->json(['error' => 'Code or Password is incorrect'], 401);
    }
}
