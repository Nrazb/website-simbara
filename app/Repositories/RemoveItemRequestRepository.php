<?php

namespace App\Repositories;

use App\Models\RemoveItemRequest;
use Illuminate\Support\Facades\Auth;

class RemoveItemRequestRepository implements RemoveItemRequestRepositoryInterface
{
    public function all()
    {
        $perPage = request()->input('per_page', 5);
        $query = RemoveItemRequest::with(['user', 'item']);

        $user = Auth::user();

        if ($user->role !== 'ADMIN') {
            $query->where('user_id', $user->id);
        }

        $userId = request()->input('user_id');
        if (!empty($userId) && $user->role === 'ADMIN') {
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

        $startDate = request()->input('start_date');
        $endDate = request()->input('end_date');
        if (!empty($startDate)) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        if (!empty($endDate)) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        return $query->paginate($perPage)
            ->appends([
                'per_page' => $perPage,
                'search' => request()->input('search'),
                'user_id' => $userId,
                'status' => $status,
                'unit_confirmed' => $unitConfirmed,
                'start_date' => request()->input('start_date'),
                'end_date' => request()->input('end_date'),
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
