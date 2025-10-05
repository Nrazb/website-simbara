<?php

namespace App\Http\Controllers\Api;

use App\Repositories\ItemRequestRepositoryInterface;
use App\Http\Resources\ItemRequestResource;
use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use App\Http\Controllers\Controller;

class ItemRequestApiController extends Controller
{
    protected $itemRequestRepository;

    public function __construct(ItemRequestRepositoryInterface $itemRequestRepository)
    {
        $this->itemRequestRepository = $itemRequestRepository;
    }

    public function index()
    {
        $itemRequests = $this->itemRequestRepository->all();
        return ItemRequestResource::collection($itemRequests);
    }

    public function show($id)
    {
        $itemRequest = $this->itemRequestRepository->find($id);
        return new ItemRequestResource($itemRequest);
    }

    public function store(StoreItemRequest $request)
    {
        $validated = $request->validated();
        $itemRequest = $this->itemRequestRepository->create($validated);
        return new ItemRequestResource($itemRequest);
    }

    public function update(UpdateItemRequest $request, $id)
    {
        $validated = $request->validated();
        $itemRequest = $this->itemRequestRepository->update($id, $validated);
        return new ItemRequestResource($itemRequest);
    }

    public function destroy($id)
    {
        $this->itemRequestRepository->delete($id);
        return response()->json(['message' => 'Item request deleted successfully.']);
    }
}
