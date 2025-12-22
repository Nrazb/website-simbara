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

        $requestStatus = request()->input('maintenance_status');
        if (!empty($requestStatus)) {
            $query->where('maintenance_status', $requestStatus);
        }

        $maintenanceUserId = request()->input('maintenance_user_id');
        if (!empty($maintenanceUserId)) {
            $query->where('maintenance_user_id', $maintenanceUserId);
        }

        $unitConfirmed = request()->input('unit_confirmed');
        if ($unitConfirmed !== null && $unitConfirmed !== '') {
            $val = ($unitConfirmed === '1' || $unitConfirmed === 1) ? 1 : 0;
            $query->where('unit_confirmed', $val);
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
                'item_status' => request()->input('item_status'),
                'maintenance_status' => request()->input('maintenance_status'),
                'maintenance_user_id' => request()->input('maintenance_user_id'),
                'unit_confirmed' => request()->input('unit_confirmed'),
                'start_date' => request()->input('start_date'),
                'end_date' => request()->input('end_date'),
            ]);
    }

    public function find($id)
    {
        return MaintenanceItemRequest::findOrFail($id);
    }

    public function create(array $data)
    {
        $payload = array_merge([
            'maintenance_status' => $data['maintenance_status'] ?? 'PENDING',
            'item_status' => $data['item_status'] ?? 'PENDING',
            'unit_confirmed' => (bool) ($data['unit_confirmed'] ?? false),
        ], $data);
        return MaintenanceItemRequest::create($payload);
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
