<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    public function all()
    {
        $perPage = request()->input('per_page', 5);
        $query = User::query()->withTrashed();

        $search = trim((string) request()->input('search', ''));
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('code', 'like', "%$search%");
            });
        }

        $role = request()->input('role');
        if (!empty($role)) {
            $query->where('role', $role);
        }

        return $query->paginate($perPage)->appends([
            'per_page' => $perPage,
            'search' => request()->input('search'),
            'role' => $role,
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
