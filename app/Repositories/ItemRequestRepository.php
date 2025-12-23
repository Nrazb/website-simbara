<?php

namespace App\Repositories;

use App\Models\ItemRequest;
use Illuminate\Support\Facades\Auth;

class ItemRequestRepository implements ItemRequestRepositoryInterface
{
    public function all()
    {
        $query = ItemRequest::query()
            ->with(['type'])
            ->withTrashed();

        $user = Auth::user();

        if ($user->role === 'ADMIN') {
            $query->where(function ($q) use ($user) {
                $q->whereNotNull('sent_at')
                    ->orWhere('user_id', $user->id);
            });
        } else {
            $query->where('user_id', $user->id);
        }

        if ($user->role === 'ADMIN') {
            $query->with(['user']);
        }

        $search = trim((string) request()->input('search', ''));
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->whereAny(['name', 'detail', 'reason', 'qty'], 'like', "%$search%")
                    ->orWhereHas('type', function ($tq) use ($search) {
                        $tq->where('name', 'like', "%$search%");
                    });
            });
        }

        $userId = request()->input('user_id');
        if (!empty($userId) && $user->role === 'ADMIN') {
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
            } elseif ($status === 'deleted') {
                $query->onlyTrashed();
            }
        }

        $start = request()->input('start');
        $end = request()->input('end');
        if (!empty($start)) {
            $query->whereDate('created_at', '>=', $start);
        }
        if (!empty($end)) {
            $query->whereDate('created_at', '<=', $end);
        }

        if ($user->role !== 'ADMIN') {
            $query->select([
                'id',
                'user_id',
                'type_id',
                'name',
                'detail',
                'qty',
                'reason',
                'sent_at',
                'created_at',
                'updated_at',
                'deleted_at',
            ]);
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
