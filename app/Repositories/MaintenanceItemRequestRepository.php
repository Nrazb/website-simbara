<?php

namespace App\Repositories;

use App\Models\MaintenanceItemRequest;

class MaintenanceItemRequestRepository implements MaintenanceItemRequestRepositoryInterface
{
    public function all($perPage = 5)
    {
        return MaintenanceItemRequest::paginate($perPage)
        ->appends(['per_page' => $perPage]);
    }

    public function find($id)
    {
        return MaintenanceItemRequest::findOrFail($id);
    }

    public function create(array $data)
    {
        return MaintenanceItemRequest::create($data);
    }

    public function update($id, array $data)
    {
        $maintenance = MaintenanceItemRequest::findOrFail($id);
        $maintenance->update($data);
        return $maintenance;
    }

    public function delete($id)
    {
        $maintenance = MaintenanceItemRequest::findOrFail($id);
        return $maintenance->delete();
    }
}
