<?php

namespace App\Http\Controllers;

use App\Repositories\MaintenanceItemRequestRepositoryInterface;
use App\Http\Requests\StoreMaintenanceItemRequest;
use App\Http\Requests\UpdateMaintenanceItemRequest;

class MaintenanceItemRequestController extends Controller
{
    protected $maintenanceItemRequestRepository;

    public function __construct(MaintenanceItemRequestRepositoryInterface $maintenanceItemRequestRepository)
    {
        $this->maintenanceItemRequestRepository = $maintenanceItemRequestRepository;
    }

    public function index()
    {
        $maintenanceItemRequests = $this->maintenanceItemRequestRepository->all();
        return view('maintenance_item_requests.index', compact('maintenanceItemRequests'));
    }

    public function create()
    {
        return view('maintenance_item_requests.create');
    }

    public function store(StoreMaintenanceItemRequest $request)
    {
        $validated = $request->validated();
        try {
            $this->maintenanceItemRequestRepository->create($validated);
            return redirect()->route('maintenance_item_requests.index')->with('success', 'Maintenance item request created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to create maintenance item request: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $maintenanceItemRequest = $this->maintenanceItemRequestRepository->find($id);
        return view('maintenance_item_requests.show', compact('maintenanceItemRequest'));
    }

    public function edit($id)
    {
        $maintenanceItemRequest = $this->maintenanceItemRequestRepository->find($id);
        return view('maintenance_item_requests.edit', compact('maintenanceItemRequest'));
    }

    public function update(UpdateMaintenanceItemRequest $request, $id)
    {
        $validated = $request->validated();
        try {
            $this->maintenanceItemRequestRepository->update($id, $validated);
            return redirect()->route('maintenance_item_requests.index')->with('success', 'Maintenance item request updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to update maintenance item request: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->maintenanceItemRequestRepository->delete($id);
            return redirect()->route('maintenance_item_requests.index')->with('success', 'Maintenance item request deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete maintenance item request: ' . $e->getMessage());
        }
    }
}
