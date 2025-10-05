<?php

namespace App\Http\Controllers\Api;

use App\Repositories\RemoveItemRequestRepositoryInterface;
use App\Http\Resources\RemoveItemRequestResource;
use App\Http\Requests\StoreRemoveItemRequest;
use App\Http\Requests\UpdateRemoveItemRequest;
use App\Http\Controllers\Controller;

class RemoveItemRequestApiController extends Controller
{
    protected $removeItemRequestRepository;

    public function __construct(RemoveItemRequestRepositoryInterface $removeItemRequestRepository)
    {
        $this->removeItemRequestRepository = $removeItemRequestRepository;
    }

    public function index()
    {
        $removeItemRequests = $this->removeItemRequestRepository->all();
        return RemoveItemRequestResource::collection($removeItemRequests);
    }

    public function show($id)
    {
        $removeItemRequest = $this->removeItemRequestRepository->find($id);
        return new RemoveItemRequestResource($removeItemRequest);
    }

    public function store(StoreRemoveItemRequest $request)
    {
        $validated = $request->validated();
        $removeItemRequest = $this->removeItemRequestRepository->create($validated);
        return new RemoveItemRequestResource($removeItemRequest);
    }

    public function update(UpdateRemoveItemRequest $request, $id)
    {
        $validated = $request->validated();
        $removeItemRequest = $this->removeItemRequestRepository->update($id, $validated);
        return new RemoveItemRequestResource($removeItemRequest);
    }

    public function destroy($id)
    {
        $this->removeItemRequestRepository->delete($id);
        return response()->json(['message' => 'Remove item request deleted successfully.']);
    }
}
