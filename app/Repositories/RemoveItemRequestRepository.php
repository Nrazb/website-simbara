<?php

namespace App\Repositories;

use App\Models\RemoveItemRequest;

class RemoveItemRequestRepository implements RemoveItemRequestRepositoryInterface
{
    public function all()
    {
        $perPage = request()->input('per_page', 5);
        $query = RemoveItemRequest::with(['user', 'item']);

        $userId = request()->input('user_id');
        if (!empty($userId)) {
            $query->where('user_id', $userId);
        }

        $status = request()->input('status');
        if (!empty($status)) {
            $query->where('status', $status);
        }

        $unitConfirmed = request()->input('unit_confirmed');
        if ($unitConfirmed !== null && $unitConfirmed !== '') {
            $val = ($unitConfirmed === '1' || $unitConfirmed === 1) ? 1 : 0;
            $query->where('unit_confirmed', $val);
        }

        $search = trim((string) request()->input('search', ''));
        if ($search !== '') {
            $query->whereHas('item', function ($q) use ($search) {
                $q->where('name', 'like', "%$search%");
            });
        }

        return $query->paginate($perPage)
            ->appends([
                'per_page' => $perPage,
                'search' => request()->input('search'),
                'user_id' => $userId,
                'status' => $status,
                'unit_confirmed' => $unitConfirmed,
            ]);
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
