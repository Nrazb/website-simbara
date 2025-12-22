<?php

namespace App\Http\Controllers\Api;

use App\Models\Item;
use App\Models\ItemRequest;
use App\Models\MaintenanceItemRequest;
use App\Models\MutationItemRequest;
use App\Models\RemoveItemRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Resources\ItemRequestResource;
use App\Http\Resources\ItemResource;

class DashboardApiController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isAdmin = $user->role === 'ADMIN';

        $itemQuery = Item::latest();
        $itemRequestQuery = ItemRequest::with(['type', 'user'])->latest();

        if (!$isAdmin) {
            $itemRequestQuery->where('user_id', $user->id);
            $itemQuery->where('user_id', $user->id);
        }

        $item = $itemQuery->take(5)->get();
        $itemRequest = $itemRequestQuery->take(5)->get();
        $totalItems = Item::count();
        $totalRequest = ItemRequest::count();
        $totalMutation = MutationItemRequest::count();
        $totalMaintenance = MaintenanceItemRequest::count();
        $totalRemove = RemoveItemRequest::count();
        return response()->json([
            'items' => ItemResource::collection($item),
            'item_requests' => ItemRequestResource::collection($itemRequest),
            'summary' => [
                'total_items' => $totalItems,
                'total_requests' => $totalRequest,
                'total_mutations' => $totalMutation,
                'total_maintenances' => $totalMaintenance,
                'total_removes' => $totalRemove,
            ],
        ]);
    }
}
