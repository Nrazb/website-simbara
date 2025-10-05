<?php

namespace App\Http\Controllers;

use App\Repositories\ItemRepositoryInterface;
use App\Http\Requests\StoreItemRequestForm;
use App\Http\Requests\UpdateItemRequestForm;

class ItemController extends Controller
{
    protected $itemRepository;

    public function __construct(ItemRepositoryInterface $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }

    public function index()
    {
        $items = $this->itemRepository->all();
        return view('items.index', compact('items'));
    }

    public function create()
    {
        return view('items.create');
    }

    public function store(StoreItemRequestForm $request)
    {
        $validated = $request->validated();
        try {
            $this->itemRepository->create($validated);
            return redirect()->route('items.index')->with('success', 'Item created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to create item: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $item = $this->itemRepository->find($id);
        return view('items.show', compact('item'));
    }

    public function edit($id)
    {
        $item = $this->itemRepository->find($id);
        return view('items.edit', compact('item'));
    }

    public function update(UpdateItemRequestForm $request, $id)
    {
        $validated = $request->validated();
        try {
            $this->itemRepository->update($id, $validated);
            return redirect()->route('items.index')->with('success', 'Item updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to update item: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->itemRepository->delete($id);
            return redirect()->route('items.index')->with('success', 'Item deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete item: ' . $e->getMessage());
        }
    }
}
