<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use App\Http\Resources\ItemRequestResource;
use App\Http\Resources\TypeResource;
use App\Http\Resources\UserResource;
use App\Models\ItemRequest;
use App\Models\User;
use App\Repositories\ItemRequestRepositoryInterface;
use App\Repositories\TypeRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemRequestApiController extends Controller
{
    protected $itemRequestRepository;

    protected $typeRepository;

    public function __construct(ItemRequestRepositoryInterface $itemRequestRepository, TypeRepositoryInterface $typeRepository)
    {
        $this->itemRequestRepository = $itemRequestRepository;
        $this->typeRepository = $typeRepository;
    }

    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 5);

        $types = $this->typeRepository->all();

        $users = User::whereIn('id', function ($query) {
            $query->select('user_id')->from('item_requests');
        })
            ->orderBy('name')
            ->get();

        $years = ItemRequest::selectRaw('YEAR(created_at) as year')
            ->when(Auth::user()->role !== 'ADMIN', function ($query) {
                $query->where('user_id', Auth::user()->id);
            })
            ->distinct()
            ->orderBy('year', 'desc')
            ->get();

        $baseQuery = $this->itemRequestRepository->all();

        if (Auth::user()->role !== 'ADMIN') {
            $baseQuery->where('user_id', Auth::user()->id);
            $baseQuery->select([
                'id',
                'user_id',
                'type_id',
                'name',
                'detail',
                'qty',
                'reason',
                'sent_at',
                'created_at',
                'updated_at',
                'deleted_at',
            ]);
        }

        if ($request->filled('status')) {
            if ($request->status === 'draft') {
                $baseQuery->whereNull('sent_at');
            } elseif ($request->status === 'sent') {
                $baseQuery->whereNotNull('sent_at');
            } elseif ($request->status === 'deleted') {
                $baseQuery->onlyTrashed();
            }
        }

        $itemRequests = $baseQuery
            ->latest()
            ->paginate($perPage)
            ->appends($request->all());

        return ItemRequestResource::collection($itemRequests)->additional([
            'types' => TypeResource::collection($types),
            'users' => UserResource::collection($users),
            'years' => $years,
        ]);
    }

    public function store(StoreItemRequest $request)
    {
        $validated = $request->validated();
        $validated['sent_at'] = null;
        $validated['user_id'] = Auth::id();

        try {
            $itemRequest = $this->itemRequestRepository->create($validated);
            $itemRequest->load(['user', 'type']);

            return (new ItemRequestResource($itemRequest))
                ->additional(['message' => 'Usulan berhasil dibuat sebagai DRAFT.'])
                ->response()
                ->setStatusCode(201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal membuat usulan: '.$e->getMessage(),
            ], 500);
        }
    }

    public function update(UpdateItemRequest $request, $id)
    {
        $item = ItemRequest::findOrFail($id);

        if ($item->sent_at !== null) {
            return response()->json([
                'message' => 'Usulan sudah dikirim dan tidak dapat diedit.',
            ], 400);
        }

        try {
            $updated = $this->itemRequestRepository->update($id, $request->validated());
            $updated->load(['user', 'type']);

            return (new ItemRequestResource($updated))
                ->additional(['message' => 'Usulan berhasil diperbarui.'])
                ->response();
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal memperbarui usulan: '.$e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        $item = ItemRequest::findOrFail($id);

        if ($item->sent_at !== null) {
            return response()->json([
                'message' => 'Usulan yang sudah dikirim tidak dapat dihapus.',
            ], 400);
        }

        try {
            $this->itemRequestRepository->delete($id);

            return response()->json([
                'message' => 'Usulan berhasil dihapus.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menghapus usulan: '.$e->getMessage(),
            ], 500);
        }
    }

    public function send($id)
    {
        $item = ItemRequest::findOrFail($id);

        if ($item->sent_at !== null) {
            return response()->json([
                'message' => 'Usulan sudah dikirim sebelumnya.',
            ], 400);
        }

        $item->update([
            'sent_at' => now(),
        ]);

        $item->refresh()->load(['user', 'type']);

        return (new ItemRequestResource($item))
            ->additional(['message' => 'Usulan berhasil dikirim.'])
            ->response();
    }

    public function show($id)
    {
        $item = ItemRequest::with(['user', 'type'])->findOrFail($id);

        return new ItemRequestResource($item);
    }
}
