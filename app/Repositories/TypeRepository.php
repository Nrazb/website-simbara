<?php

namespace App\Repositories;

use App\Models\Type;

class TypeRepository implements TypeRepositoryInterface
{
    public function all($perPage = 5, $filters=[])
    {
        $query = Type::query()->withTrashed();
        return $query->paginate($perPage)->appends([
            'per_page' => $perPage,
            $filters
        ]);
    }

    public function find($id)
    {
        return Type::findOrFail($id);
    }

    public function create(array $data)
    {
        return Type::create($data);
    }

    public function update($id, array $data)
    {
        $type = Type::findOrFail($id);
        $type->update($data);
        return $type;
    }

    public function delete($id)
    {
        $type = Type::findOrFail($id);
        return $type->delete();
    }
}
