<?php

namespace App\Http\Controllers;

use App\Repositories\RemoveItemRequestRepositoryInterface;
use App\Http\Requests\StoreRemoveItemRequest;
use App\Http\Requests\UpdateRemoveItemRequest;

class RemoveItemRequestController extends Controller
{
    protected $removeItemRequestRepository;

    public function __construct(RemoveItemRequestRepositoryInterface $removeItemRequestRepository)
    {
        $this->removeItemRequestRepository = $removeItemRequestRepository;
    }

    public function index()
    {
        $removeItemRequests = $this->removeItemRequestRepository->all();
        return view('remove_item_requests.index', compact('removeItemRequests'));
    }

    public function create()
    {
        return view('remove_item_requests.create');
    }

    public function store(StoreRemoveItemRequest $request)
    {
        $validated = $request->validated();
        try {
            $this->removeItemRequestRepository->create($validated);
            return redirect()->route('remove_item_requests.index')->with('success', 'Remove item request created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to create remove item request: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $removeItemRequest = $this->removeItemRequestRepository->find($id);
        return view('remove_item_requests.show', compact('removeItemRequest'));
    }

    public function edit($id)
    {
        $removeItemRequest = $this->removeItemRequestRepository->find($id);
        return view('remove_item_requests.edit', compact('removeItemRequest'));
    }

    public function update(UpdateRemoveItemRequest $request, $id)
    {
        $validated = $request->validated();
        try {
            $this->removeItemRequestRepository->update($id, $validated);
            return redirect()->route('remove_item_requests.index')->with('success', 'Remove item request updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to update remove item request: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->removeItemRequestRepository->delete($id);
            return redirect()->route('remove_item_requests.index')->with('success', 'Remove item request deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete remove item request: ' . $e->getMessage());
        }
    }
}
