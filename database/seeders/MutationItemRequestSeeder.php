<?php

namespace Database\Seeders;

use App\Models\MutationItemRequest;
use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MutationItemRequestSeeder extends Seeder
{
    public function run(): void
    {
        $projector = Item::where('code', '3020101101')->where('order_number', 1)->first();
        $from = User::where('code', 'UPATIK')->first();
        $to = User::where('code', 'RPLUNIT')->first();
        $xrayViewer = Item::where('code', '3070106150')->where('order_number', 1)->first();
        $fromKp = User::where('code', 'KEPERAWATAN')->first();
        $toTi = User::where('code', 'TIUNIT')->first();

        if ($projector && $from && $to) {
            MutationItemRequest::create([
                'item_id' => $projector->id,
                'from_user_id' => $from->id,
                'to_user_id' => $to->id,
                'unit_confirmed' => false,
                'recipient_confirmed' => false,
            ]);
        }

        if ($xrayViewer && $fromKp && $toTi) {
            MutationItemRequest::create([
                'item_id' => $xrayViewer->id,
                'from_user_id' => $fromKp->id,
                'to_user_id' => $toTi->id,
                'unit_confirmed' => false,
                'recipient_confirmed' => false,
            ]);
        }
    }
}
