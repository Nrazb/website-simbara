<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    // Show the login form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('code', 'password');
        if ($this->userRepository->attemptLogin($credentials, $request->filled('remember'))) {
            return redirect()->route("dashboard");
        }
        return back()->withErrors(['code' => 'Code or Password is incorrect'])->withInput($request->only('code'));
    }

    public function logout(Request $request)
    {
        Auth::logout(); 

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'You have been logged out.');
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
