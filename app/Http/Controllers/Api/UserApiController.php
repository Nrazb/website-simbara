<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepositoryInterface;
use App\Http\Resources\UserResource;

class UserApiController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index(\Illuminate\Http\Request $request)
    {
        $users = $this->userRepository->all();
        return UserResource::collection($users);
    }

    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();
        try {
            $validated['password'] = \Illuminate\Support\Facades\Hash::make($validated['password']);
            $user = $this->userRepository->create($validated);
            return (new UserResource($user))
                ->additional(['message' => 'Berhasil menambahkan pengguna baru.'])
                ->response()
                ->setStatusCode(201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal membuat pengguna: ' . $e->getMessage(),
            ], 500);
        }
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
            $user = $this->userRepository->update($id, $validated);
            return (new UserResource($user))
                ->additional(['message' => 'Data berhasil diperbarui.'])
                ->response();
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal memperbarui: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $this->userRepository->delete($id);
            return response()->json([
                'message' => 'Berhasil menghapus pengguna.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menghapus pengguna: ' . $e->getMessage(),
            ], 500);
        }
    }
}
