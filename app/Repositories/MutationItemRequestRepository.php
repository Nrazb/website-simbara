<?php

namespace App\Repositories;

use App\Models\MutationItemRequest;

class MutationItemRequestRepository implements MutationItemRequestRepositoryInterface
{
    public function all()
    {
        return MutationItemRequest::all();
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
}
