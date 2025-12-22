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

    public function index(Request $request)
    {
        $users = $this->userRepository->all();
        return view('users.index', compact('users'));
    }


    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();
        try {
            $validated['password'] = Hash::make($validated['password']);
            $this->userRepository->create($validated);
            return redirect()->route('users.index')->with('success', 'Berhasil menambahkan pengguna baru.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal membuat pengguna: ' . $e->getMessage());
        }
    }


    public function update(UpdateUserRequest $request, $id)
    {
        $validated = $request->validated();
        try {
            if (!empty($validated['password'])) {
                $validated['password'] = Hash::make($validated['password']);
            } else {
                unset($validated['password']);
            }
            $this->userRepository->update($id, $validated);
            return redirect()->route('users.index')->with('success', 'Data berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->userRepository->delete($id);
            return redirect()->route('users.index')->with('success', 'Berhasil menghapus pengguna.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus pengguna: ' . $e->getMessage());
        }
    }
}
