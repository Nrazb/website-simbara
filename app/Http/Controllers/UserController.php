<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Repositories\UserRepositoryInterface;

class UserController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        $users = $this->userRepository->all();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();
        try {
            $validated['password'] = \Illuminate\Support\Facades\Hash::make($validated['password']);
            $this->userRepository->create($validated);
            return redirect()->route('users.index')->with('success', 'User created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to create user: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $user = $this->userRepository->find($id);
        return view('users.show', compact('user'));
    }

    public function edit($id)
    {
        $user = $this->userRepository->find($id);
        return view('users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $validated = $request->validated();
        try {
            if (!empty($validated['password'])) {
                $validated['password'] = \Illuminate\Support\Facades\Hash::make($validated['password']);
            } else {
                unset($validated['password']);
            }
            $this->userRepository->update($id, $validated);
            return redirect()->route('users.index')->with('success', 'User updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to update user: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->userRepository->delete($id);
            return redirect()->route('users.index')->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete user: ' . $e->getMessage());
        }
    }
}
