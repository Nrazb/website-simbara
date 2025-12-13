<?php

namespace App\Repositories\Api;

use App\Models\RemoveItemRequest;

class RemoveItemRequestRepository implements RemoveItemRequestRepositoryInterface
{
    public function all($perPage = 5, $filters = [])
    {
        $query = RemoveItemRequest::query()->with('user');

        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        return $query->paginate($perPage)
                    ->appends($filters)
                    ->appends(['per_page' => $perPage]);
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

