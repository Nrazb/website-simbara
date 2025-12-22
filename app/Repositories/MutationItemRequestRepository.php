<?php

namespace App\Repositories;

use App\Models\MutationItemRequest;
use Illuminate\Support\Facades\Auth;

class MutationItemRequestRepository implements MutationItemRequestRepositoryInterface
{
    public function all($perPage = 5)
    {
        $perPage = request()->input('per_page', $perPage);
        $query = MutationItemRequest::with(['fromUser', 'toUser', 'item']);

        if (Auth::user() && Auth::user()->role !== 'ADMIN') {
            $query->where(function ($q) {
                $q->where('from_user_id', Auth::user()->id)
                  ->orWhere('to_user_id', Auth::user()->id);
            });
        } else {
            $query->withTrashed();
        }

        $search = trim((string) request()->input('search', ''));
        if ($search !== '') {
            $query->whereHas('item', function ($q) use ($search) {
                $q->where('name', 'like', "%$search%");
            });
        }

        $fromUserId = request()->input('from_user_id');
        if (!empty($fromUserId)) {
            $query->where('from_user_id', $fromUserId);
        }

        $toUserId = request()->input('to_user_id');
        if (!empty($toUserId)) {
            $query->where('to_user_id', $toUserId);
        }

        $unitConfirmed = request()->input('unit_confirmed');
        if ($unitConfirmed !== null && $unitConfirmed !== '') {
            $val = ($unitConfirmed === '1' || $unitConfirmed === 1) ? 1 : 0;
            $query->where('unit_confirmed', $val);
        }

        $recipientConfirmed = request()->input('recipient_confirmed');
        if ($recipientConfirmed !== null && $recipientConfirmed !== '') {
            $val = ($recipientConfirmed === '1' || $recipientConfirmed === 1) ? 1 : 0;
            $query->where('recipient_confirmed', $val);
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
                'from_user_id' => $fromUserId,
                'to_user_id' => $toUserId,
                'unit_confirmed' => $unitConfirmed,
                'recipient_confirmed' => $recipientConfirmed,
                'start_date' => $startDate,
                'end_date' => $endDate,
            ]);
    }

    public function find($id)
    {
        return MutationItemRequest::findOrFail($id);
    }

    public function create(array $data)
    {
        return MutationItemRequest::create($data);
    }

    public function update($id, array $data)
    {
        $mutation = MutationItemRequest::findOrFail($id);
        $mutation->update($data);
        return $mutation;
    }

    public function delete($id)
    {
        $mutation = MutationItemRequest::findOrFail($id);
        return $mutation->delete();
    }

    public function confirm($id, string $field)
    {
        if (!in_array($field, ['unit_confirmed', 'recipient_confirmed'])) {
            throw new \InvalidArgumentException('Invalid confirmation field');
        }
        $mutation = MutationItemRequest::findOrFail($id);
        $mutation->update([$field => true]);
        return $mutation;
    }
}
