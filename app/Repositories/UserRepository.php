<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    public function all($perPage = 5, $filters=[])
    {
        $query = User::query()->withTrashed();
        return $query->paginate($perPage)->appends([
            'per_page' => $perPage,
            $filters
        ]);
    }

    public function find($id)
    {
        return User::findOrFail($id);
    }

    public function create(array $data)
    {
        return User::create($data);
    }

    public function update($id, array $data)
    {
        $user = User::findOrFail($id);
        $user->update($data);
        return $user;
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);
        return $user->delete();
    }

    public function attemptLogin(array $credentials, $remember = false)
    {
        return Auth::attempt([
            'code' => $credentials['code'],
            'password' => $credentials['password'],
        ], $remember);
    }

    public function attemptApiLogin(array $credentials)
    {
        $user = User::where('code', $credentials['code'])->first();
        if ($user && Hash::check($credentials['password'], $user->password)) {
            return $user;
        }
        return null;
    }
}
