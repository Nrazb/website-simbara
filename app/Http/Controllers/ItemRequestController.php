<?php

namespace App\Http\Controllers;

use App\Repositories\ItemRequestRepositoryInterface;
use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use App\Repositories\TypeRepositoryInterface;

class ItemRequestController extends Controller
{
    protected $itemRequestRepository;
    protected $typeRepository;

    public function __construct(ItemRequestRepositoryInterface $itemRequestRepository, TypeRepositoryInterface $typeRepository)
    {
        $this->itemRequestRepository = $itemRequestRepository;
        $this->typeRepository = $typeRepository;
    }

    public function index()
    {
        $itemRequests = $this->itemRequestRepository->all()->load(['type', 'user']);
        $types = $this->typeRepository->all();
        return view('item_requests.index', compact('itemRequests', 'types'));
    }

    public function create()
    {
        $types = $this->typeRepository->all();
        return view('item_requests.create', compact('types'));
    }

    public function store(StoreItemRequest $request)
    {
        $validated = $request->validated();
        try {
            $this->itemRequestRepository->create($validated);
            return redirect()->route('item_requests.index')->with('success', 'Item request created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to create item request: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $itemRequest = $this->itemRequestRepository->find($id);
        return view('item_requests.show', compact('itemRequest'));
    }

    public function edit($id)
    {
        $itemRequest = $this->itemRequestRepository->find($id);
        return view('item_requests.edit', compact('itemRequest'));
    }

    public function update(UpdateItemRequest $request, $id)
    {
        $validated = $request->validated();
        try {
            $this->itemRequestRepository->update($id, $validated);
            return redirect()->route('item_requests.index')->with('success', 'Item request updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to update item request: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->itemRequestRepository->delete($id);
            return redirect()->route('item_requests.index')->with('success', 'Item request deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete item request: ' . $e->getMessage());
        }
    }
}
