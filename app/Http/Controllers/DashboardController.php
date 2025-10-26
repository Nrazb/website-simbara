<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemRequest;
use App\Models\MaintenanceItemRequest;
use App\Models\MutationItemRequest;
use App\Models\RemoveItemRequest;

class DashboardController extends Controller
{
    public function index()
    {
        $item = Item::latest()->take(5)->get();
        $itemRequest = ItemRequest::with(['type', 'user'])->latest()->take(5)->get();
        $totalItems = Item::count();
        $totalRequest = ItemRequest::count();
        $totalMutation = MutationItemRequest::count();
        $totalMaintenance = MaintenanceItemRequest::count();
        $totalRemove = RemoveItemRequest::count();
        return view('dashboard', compact('item', 'itemRequest', 'totalItems', 'totalRequest', 'totalMutation', 'totalMaintenance', 'totalRemove'));
    }
}
