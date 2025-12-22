<?php

namespace App\Http\Controllers\Api;

use App\Repositories\MutationItemRequestRepositoryInterface;
use App\Http\Requests\StoreMutationItemRequest;
use App\Http\Controllers\Controller;
use App\Repositories\ItemRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Models\MutationItemRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\MutationItemRequestResource;

class MutationItemRequestApiController extends Controller
{
    protected $mutationItemRequestRepository;
    protected $itemRepository;
    protected $userRepository;

    public function __construct(MutationItemRequestRepositoryInterface $mutationItemRequestRepository, ItemRepositoryInterface $itemRepository, UserRepositoryInterface $userRepository)
    {
        $this->mutationItemRequestRepository = $mutationItemRequestRepository;
        $this->itemRepository = $itemRepository;
        $this->userRepository = $userRepository;
    }

    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 5);
        $mutationItemRequests = $this->mutationItemRequestRepository->all($perPage);
        $users = null;
        if (Auth::user()?->role === 'ADMIN') {
            $users = \App\Models\User::where('role', 'UNIT')->orderBy('name')->get();
        }
        return MutationItemRequestResource::collection($mutationItemRequests)->additional([
            'users' => $users,
        ]);
    }

    public function store(StoreMutationItemRequest $request)
    {
        $validated = $request->validated();
        try {
            $mutationItemRequest = $this->mutationItemRequestRepository->create($validated);
            return (new MutationItemRequestResource($mutationItemRequest))
                ->additional(['message' => 'Permintaan mutasi berhasil dibuat.'])
                ->response()
                ->setStatusCode(201);
        } catch (QueryException $e) {
            $code = $e->getCode();
            $msg = $code == 23000 ? 'Data duplikat atau referensi tidak valid.' : 'Kesalahan database. Coba lagi.';
            return response()->json([
                'message' => $msg,
            ], 500);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Failed to create mutation item request.',
            ], 500);
        }
    }

    public function confirm(Request $request, MutationItemRequest $mutationItemRequest)
    {
        $data = $request->validate([
            'target' => 'required|in:unit,recipient',
        ]);

        $userId = Auth::user()->id;
        $field = $data['target'] === 'unit' ? 'unit_confirmed' : 'recipient_confirmed';

        if ($data['target'] === 'unit' && $userId !== $mutationItemRequest->from_user_id) {
            return response()->json([
                'message' => 'Hanya unit asal yang dapat mengkonfirmasi.',
            ], 403);
        }

        if ($data['target'] === 'recipient' && $userId !== $mutationItemRequest->to_user_id) {
            return response()->json([
                'message' => 'Hanya unit tujuan yang dapat mengkonfirmasi.',
            ], 403);
        }

        if ($mutationItemRequest->{$field}) {
            return (new MutationItemRequestResource($mutationItemRequest))
                ->additional(['message' => 'Status sudah dikonfirmasi.'])
                ->response();
        }

        try {
            $this->mutationItemRequestRepository->confirm($mutationItemRequest->id, $field);
            return (new MutationItemRequestResource($mutationItemRequest->refresh()))
                ->additional(['message' => 'Konfirmasi berhasil.'])
                ->response();
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Kesalahan database saat konfirmasi.',
            ], 500);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Gagal mengkonfirmasi.',
            ], 500);
        }
    }
}
