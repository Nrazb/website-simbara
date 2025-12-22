<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\LoginRequest;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

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
                'data' => $user,
            ]);
        }
        return response()->json(['error' => 'Code or Password is incorrect'], 401);
    }
}
