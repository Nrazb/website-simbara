<?php

namespace App\Repositories;

use App\Models\Item;

class ItemRepository implements ItemRepositoryInterface
{
    public function all()
    {
        return Item::all();
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
