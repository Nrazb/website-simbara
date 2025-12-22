<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRemoveItemUnitRequest;
use App\Http\Resources\ItemResource;
use App\Http\Resources\RemoveItemRequestResource;
use App\Http\Resources\UserResource;
use App\Models\Item;
use App\Models\RemoveItemRequest;
use App\Models\User;
use App\Repositories\RemoveItemRequestRepositoryInterface;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

class RemoveItemRequestApiController extends Controller
{
    protected $removeItemRequestRepository;

    public function __construct(RemoveItemRequestRepositoryInterface $removeItemRequestRepository)
    {
        $this->removeItemRequestRepository = $removeItemRequestRepository;
    }

    public function index(Request $request)
    {
        $users = User::whereIn('id', function ($query) {
            $query->select('user_id')->from('remove_item_requests');
        })
            ->orderBy('name')
            ->get();

        $removeItemRequests = $this->removeItemRequestRepository->all();

        $items = null;
        if (Auth::user()->role !== 'ADMIN') {
            $items = Item::where('user_id', Auth::id())->latest()->get();
        }

        return RemoveItemRequestResource::collection($removeItemRequests)->additional([
            'users' => UserResource::collection($users),
            'items' => $items ? ItemResource::collection($items) : null,
        ]);
    }

    public function store(StoreRemoveItemUnitRequest $request)
    {
        $validated = $request->validated();

        try {
            $data = [
                'user_id' => Auth::id(),
                'item_id' => $validated['item_id'],
                'status' => 'PROCESS',
                'unit_confirmed' => false,
            ];
            $removeItemRequest = $this->removeItemRequestRepository->create($data);
            $removeItemRequest->load(['user', 'item']);

            return (new RemoveItemRequestResource($removeItemRequest))
                ->additional(['message' => 'Pengajuan penghapusan barang ditambahkan.'])
                ->response()
                ->setStatusCode(201);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Kesalahan database saat menambahkan pengajuan.',
            ], 500);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Gagal menambahkan pengajuan.',
            ], 500);
        }
    }

    public function confirmUnit(RemoveItemRequest $removeItemRequest)
    {
        $user = Auth::user();
        try {
            if ($user->id !== $removeItemRequest->user_id) {
                return response()->json([
                    'message' => 'Akses ditolak.',
                ], 403);
            }
            if ($removeItemRequest->unit_confirmed) {
                return (new RemoveItemRequestResource($removeItemRequest))
                    ->additional(['message' => 'Unit sudah dikonfirmasi.'])
                    ->response();
            }
            $this->removeItemRequestRepository->update($removeItemRequest->id, ['unit_confirmed' => true]);
            $removeItemRequest->refresh()->load(['user', 'item']);

            return (new RemoveItemRequestResource($removeItemRequest))
                ->additional(['message' => 'Unit dikonfirmasi.'])
                ->response();
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Kesalahan database saat memproses.',
            ], 500);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Gagal memproses.',
            ], 500);
        }
    }

    public function updateStatus(Request $request, RemoveItemRequest $removeItemRequest)
    {
        $user = Auth::user();
        try {
            if ($user->role !== 'ADMIN') {
                return response()->json([
                    'message' => 'Akses ditolak.',
                ], 403);
            }
            $status = $request->input('status');
            $allowed = ['PROCESS', 'STORED', 'AUCTIONED'];
            if (! in_array($status, $allowed, true)) {
                return response()->json([
                    'message' => 'Status tidak valid.',
                ], 400);
            }
            $this->removeItemRequestRepository->update($removeItemRequest->id, ['status' => $status]);
            $removeItemRequest->refresh()->load(['user', 'item']);

            return (new RemoveItemRequestResource($removeItemRequest))
                ->additional(['message' => 'Status penghapusan diperbarui.'])
                ->response();
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Kesalahan database saat memproses.',
            ], 500);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Gagal memproses.',
            ], 500);
        }
    }
}
