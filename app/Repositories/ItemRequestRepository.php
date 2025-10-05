<?php

namespace App\Repositories;

use App\Models\ItemRequest;

class ItemRequestRepository implements ItemRequestRepositoryInterface
{
    public function all()
    {
        return ItemRequest::all();
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
