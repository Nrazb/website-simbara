<?php

namespace App\Http\Controllers\Api;

use App\Repositories\MaintenanceItemRequestRepositoryInterface;
use App\Http\Resources\MaintenanceItemRequestResource;
use App\Http\Requests\StoreMaintenanceItemRequest;
use App\Http\Requests\UpdateMaintenanceItemRequest;
use App\Http\Controllers\Controller;

class MaintenanceItemRequestApiController extends Controller
{
    protected $maintenanceItemRequestRepository;

    public function __construct(MaintenanceItemRequestRepositoryInterface $maintenanceItemRequestRepository)
    {
        $this->maintenanceItemRequestRepository = $maintenanceItemRequestRepository;
    }

    public function index()
    {
        $maintenanceItemRequests = $this->maintenanceItemRequestRepository->all();
        return MaintenanceItemRequestResource::collection($maintenanceItemRequests);
    }

    public function show($id)
    {
        $maintenanceItemRequest = $this->maintenanceItemRequestRepository->find($id);
        return new MaintenanceItemRequestResource($maintenanceItemRequest);
    }

    public function store(StoreMaintenanceItemRequest $request)
    {
        $validated = $request->validated();
        $maintenanceItemRequest = $this->maintenanceItemRequestRepository->create($validated);
        return new MaintenanceItemRequestResource($maintenanceItemRequest);
    }

    public function update(UpdateMaintenanceItemRequest $request, $id)
    {
        $validated = $request->validated();
        $maintenanceItemRequest = $this->maintenanceItemRequestRepository->update($id, $validated);
        return new MaintenanceItemRequestResource($maintenanceItemRequest);
    }

    public function destroy($id)
    {
        $this->maintenanceItemRequestRepository->delete($id);
        return response()->json(['message' => 'Maintenance item request deleted successfully.']);
    }
}
