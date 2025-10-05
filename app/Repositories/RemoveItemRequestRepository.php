<?php

namespace App\Repositories;

use App\Models\RemoveItemRequest;

class RemoveItemRequestRepository implements RemoveItemRequestRepositoryInterface
{
    public function all()
    {
        return RemoveItemRequest::all();
    }

    public function find($id)
    {
        return RemoveItemRequest::findOrFail($id);
    }

    public function create(array $data)
    {
        return RemoveItemRequest::create($data);
    }

    public function update($id, array $data)
    {
        $remove = RemoveItemRequest::findOrFail($id);
        $remove->update($data);
        return $remove;
    }

    public function delete($id)
    {
        $remove = RemoveItemRequest::findOrFail($id);
        return $remove->delete();
    }
}
