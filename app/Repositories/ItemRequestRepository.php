<?php

namespace App\Repositories;

use App\Models\ItemRequest;

class ItemRequestRepository implements ItemRequestRepositoryInterface
{
    public function all()
    {
        $query = ItemRequest::query()
            ->with(['type', 'user'])
            ->withTrashed();

        $search = trim((string) request()->input('search', ''));
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('detail', 'like', "%$search%")
                    ->orWhereHas('type', function ($t) use ($search) {
                        $t->where('name', 'like', "%$search%");
                    });
            });
        }

        $userId = request()->input('user_id');
        if (!empty($userId)) {
            $query->where('user_id', $userId);
        }

        $year = request()->input('year');
        if (!empty($year)) {
            $query->whereYear('created_at', $year);
        }

        $status = request()->input('status');
        if (!empty($status)) {
            if ($status === 'draft') {
                $query->whereNull('sent_at');
            } elseif ($status === 'sent') {
                $query->whereNotNull('sent_at');
            }
        }

        return $query;
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
