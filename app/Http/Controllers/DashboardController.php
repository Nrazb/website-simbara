<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemRequest;
use App\Models\MaintenanceItemRequest;
use App\Models\MutationItemRequest;
use App\Models\RemoveItemRequest;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isAdmin = $user->role === 'ADMIN';

        $itemQuery = Item::latest();
        $itemRequestQuery = ItemRequest::with(['type', 'user'])->latest();

        if ($isAdmin) {
            $itemRequestQuery->where(function ($q) use ($user) {
                $q->whereNotNull('sent_at')
                    ->orWhere('user_id', $user->id);
            });
        } else {
            $itemRequestQuery->where('user_id', $user->id);
            $itemQuery->where('user_id', $user->id);
        }

        $item = $itemQuery->take(5)->get();
        $itemRequest = $itemRequestQuery->take(5)->get();
        $totalItems = Item::count();
        $totalRequestQuery = ItemRequest::query();
        if ($isAdmin) {
            $totalRequestQuery->where(function ($q) use ($user) {
                $q->whereNotNull('sent_at')
                    ->orWhere('user_id', $user->id);
            });
        } else {
            $totalRequestQuery->where('user_id', $user->id);
        }
        $totalRequest = $totalRequestQuery->count();
        $totalMutation = MutationItemRequest::count();
        $totalMaintenance = MaintenanceItemRequest::count();
        $totalRemove = RemoveItemRequest::count();
        return view('dashboard', compact('item', 'itemRequest', 'totalItems', 'totalRequest', 'totalMutation', 'totalMaintenance', 'totalRemove'));
    }
}
