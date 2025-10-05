<?php

namespace App\Http\Controllers\Api;

use App\Repositories\ItemRepositoryInterface;
use App\Http\Resources\ItemResource;
use App\Http\Requests\StoreItemRequestForm;
use App\Http\Requests\UpdateItemRequestForm;
use App\Http\Controllers\Controller;

class ItemApiController extends Controller
{
    protected $itemRepository;

    public function __construct(ItemRepositoryInterface $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }

    public function index()
    {
        $items = $this->itemRepository->all();
        return ItemResource::collection($items);
    }

    public function show($id)
    {
        $item = $this->itemRepository->find($id);
        return new ItemResource($item);
    }

    public function store(StoreItemRequestForm $request)
    {
        $validated = $request->validated();
        $item = $this->itemRepository->create($validated);
        return new ItemResource($item);
    }

    public function update(UpdateItemRequestForm $request, $id)
    {
        $validated = $request->validated();
        $item = $this->itemRepository->update($id, $validated);
        return new ItemResource($item);
    }

    public function destroy($id)
    {
        $this->itemRepository->delete($id);
        return response()->json(['message' => 'Item deleted successfully.']);
    }
}
