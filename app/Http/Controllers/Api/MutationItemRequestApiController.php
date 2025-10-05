<?php

namespace App\Http\Controllers\Api;

use App\Repositories\MutationItemRequestRepositoryInterface;
use App\Http\Resources\MutationItemRequestResource;
use App\Http\Requests\StoreMutationItemRequest;
use App\Http\Requests\UpdateMutationItemRequest;
use App\Http\Controllers\Controller;

class MutationItemRequestApiController extends Controller
{
    protected $mutationItemRequestRepository;

    public function __construct(MutationItemRequestRepositoryInterface $mutationItemRequestRepository)
    {
        $this->mutationItemRequestRepository = $mutationItemRequestRepository;
    }

    public function index()
    {
        $mutationItemRequests = $this->mutationItemRequestRepository->all();
        return MutationItemRequestResource::collection($mutationItemRequests);
    }

    public function show($id)
    {
        $mutationItemRequest = $this->mutationItemRequestRepository->find($id);
        return new MutationItemRequestResource($mutationItemRequest);
    }

    public function store(StoreMutationItemRequest $request)
    {
        $validated = $request->validated();
        $mutationItemRequest = $this->mutationItemRequestRepository->create($validated);
        return new MutationItemRequestResource($mutationItemRequest);
    }

    public function update(UpdateMutationItemRequest $request, $id)
    {
        $validated = $request->validated();
        $mutationItemRequest = $this->mutationItemRequestRepository->update($id, $validated);
        return new MutationItemRequestResource($mutationItemRequest);
    }

    public function destroy($id)
    {
        $this->mutationItemRequestRepository->delete($id);
        return response()->json(['message' => 'Mutation item request deleted successfully.']);
    }
}
