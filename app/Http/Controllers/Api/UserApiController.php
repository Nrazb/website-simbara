<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Resources\UserResource;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepositoryInterface;

class UserApiController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        return UserResource::collection($this->userRepository->all());
    }

    public function show($id)
    {
        $user = $this->userRepository->find($id);
        return new UserResource($user);
    }

    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();
        $validated['password'] = \Illuminate\Support\Facades\Hash::make($validated['password']);
        $user = $this->userRepository->create($validated);
        return new UserResource($user);
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $validated = $request->validated();
        if (!empty($validated['password'])) {
            $validated['password'] = \Illuminate\Support\Facades\Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }
        $user = $this->userRepository->update($id, $validated);
        return new UserResource($user);
    }

    public function destroy($id)
    {
        $this->userRepository->delete($id);
        return response()->json(['message' => 'User deleted successfully.']);
    }
}
