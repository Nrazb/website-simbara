<?php

namespace App\Repositories;

use App\Models\MaintenanceItemRequest;

class MaintenanceItemRequestRepository implements MaintenanceItemRequestRepositoryInterface
{
    public function all()
    {
        $perPage = request()->input('per_page', 5);
        $query = MaintenanceItemRequest::with(['user', 'item']);

        $search = trim((string) request()->input('search', ''));
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('information', 'like', "%$search%")
                    ->orWhereHas('item', function ($iq) use ($search) {
                        $iq->where('name', 'like', "%$search%");
                    });
            });
        }

        $itemStatus = request()->input('item_status');
        if (!empty($itemStatus)) {
            $query->where('item_status', $itemStatus);
        }

        $requestStatus = request()->input('request_status');
        if (!empty($requestStatus)) {
            $query->where('request_status', $requestStatus);
        }

        $unitConfirmed = request()->input('unit_confirmed');
        if ($unitConfirmed !== null && $unitConfirmed !== '') {
            $val = ($unitConfirmed === '1' || $unitConfirmed === 1) ? 1 : 0;
            $query->where('unit_confirmed', $val);
        }

        return $query->paginate($perPage)
            ->appends([
                'per_page' => $perPage,
                'search' => request()->input('search'),
                'item_status' => request()->input('item_status'),
                'request_status' => request()->input('request_status'),
                'unit_confirmed' => request()->input('unit_confirmed'),
            ]);
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
