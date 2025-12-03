<?php

namespace App\Repositories;

use App\Models\Item;

class ItemRepository implements ItemRepositoryInterface
{
    public function all($search = null, $perPage = 5, $filters=[])
    {
        $query = Item::query()->with(['user'])->withTrashed();

        $query
            ->when(!empty($filters['user_id']), function ($q) use ($filters) {
                $q->where('user_id', $filters['user_id']);
            })
            ->when(!empty($filters['year']), function ($q) use ($filters) {
                $q->whereYear('created_at', $filters['year']);
            });

        return $query->paginate($perPage)
                     ->appends([
                         'search' => $search,
                         'per_page' => $perPage,
                         $filters
                     ]);
    }

    public function find($id)
    {
        return Item::findOrFail($id);
    }

    public function create(array $data)
    {
        return Item::create($data);
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
