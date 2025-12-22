<?php

namespace App\Repositories;

use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class ItemRepository implements ItemRepositoryInterface
{
    public function all($search = null, $perPage = 5, $filters=[])
    {
        $query = Item::query()->withTrashed()->with(['type']);

        if (Auth::user() && Auth::user()->role === 'ADMIN') {
            $query->with(['user']);
        } else {
            $query->where('user_id', Auth::id());
        }

        $search = trim((string) (request()->input('search', $search ?? '')));
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->whereAny(['id', 'code', 'order_number', 'name', 'cost'], 'like', "%$search%")
                  ->orWhereHas('type', function ($t) use ($search) {
                      $t->where('name', 'like', "%$search%");
                  });
            });
        }

        $userId = request()->input('user_id');
        if (!empty($userId) && Auth::user() && Auth::user()->role === 'ADMIN') {
            $query->where('user_id', $userId);
        }

        $year = request()->input('year');
        if (!empty($year)) {
            $query->where('acquisition_year', $year);
        }

        $startDate = request()->input('start_date');
        $endDate = request()->input('end_date');
        if (!empty($startDate)) {
            $query->whereDate('acquisition_date', '>=', $startDate);
        }
        if (!empty($endDate)) {
            $query->whereDate('acquisition_date', '<=', $endDate);
        }

        $minCost = request()->input('min_cost');
        $maxCost = request()->input('max_cost');
        if ($minCost !== null && $minCost !== '') {
            $query->where('cost', '>=', (float) $minCost);
        }
        if ($maxCost !== null && $maxCost !== '') {
            $query->where('cost', '<=', (float) $maxCost);
        }

        if (Auth::user() && Auth::user()->role !== 'ADMIN') {
            $query->select([
                'id',
                'type_id',
                'code',
                'order_number',
                'name',
                'cost',
                'acquisition_date',
                'acquisition_year',
                'status',
                'created_at',
                'updated_at',
                'deleted_at',
            ]);
        }

        return $query->latest()->paginate($perPage)->appends(request()->all());
    }

    public function find($id)
    {
        return Item::findOrFail($id);
    }

    public function create(array $data)
    {
        return Item::insert($data);
    }

    public function update($id, array $data)
    {
        $item = Item::findOrFail($id);
        $item->update($data);
        return $item;
    }

    public function delete($id)
    {
        $item = Item::findOrFail($id);
        return $item->delete();
    }
}
