<?php

namespace Database\Seeders;

use App\Models\RemoveItemRequest;
use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RemoveItemRequestSeeder extends Seeder
{
    public function run(): void
    {
        $router = Item::where('code', '4110002002')->where('order_number', 1)->first();
        $upa = User::where('code', 'UPATIK')->first();

        if ($router && $upa) {
            RemoveItemRequest::create([
                'user_id' => $upa->id,
                'item_id' => $router->id,
                'status' => 'PROCESS',
                'unit_confirmed' => false,
            ]);
        }
    }
}
