<?php

namespace App\Repositories;

use App\Models\ItemRequest;

class ItemRequestRepository implements ItemRequestRepositoryInterface
{
    public function all($perPage = 5, $filters = [])
    {
        $query = ItemRequest::query()->with(['type', 'user'])->withTrashed();

        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (!empty($filters['year'])) {
            $query->whereYear('created_at', $filters['year']);
        }

        return $query->paginate($perPage)
        ->appends(['per_page' => $perPage, $filters]);
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
