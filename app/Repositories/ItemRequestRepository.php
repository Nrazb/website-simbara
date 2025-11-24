<?php

namespace App\Repositories;

use App\Models\ItemRequest;

class ItemRequestRepository implements ItemRequestRepositoryInterface
{
    public function all($perPage = 5)
    {
        return ItemRequest::with(['type', 'user'])
        ->paginate($perPage)
        ->appends(['per_page' => $perPage]);
    }

    public function find($id)
    {
        return ItemRequest::findOrFail($id);
    }

    public function create(array $data)
    {
        return ItemRequest::create($data);
    }

    public function update($id, array $data)
    {
        $itemRequest = ItemRequest::findOrFail($id);
        $itemRequest->update($data);
        return $itemRequest;
    }

    public function delete($id)
    {
        $itemRequest = ItemRequest::findOrFail($id);
        return $itemRequest->delete();
    }
}
