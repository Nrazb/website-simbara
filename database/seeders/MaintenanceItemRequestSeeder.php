<?php

namespace Database\Seeders;

use App\Models\MaintenanceItemRequest;
use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MaintenanceItemRequestSeeder extends Seeder
{
    public function run(): void
    {
        $kp = User::where('code', 'KEPERAWATAN')->first();
        $upa = User::where('code', 'UPATIK')->first();
        $maintenanceUpp = User::where('code', 'UPP')->first();
        $maintenanceUmum = User::where('code', 'UMUM')->first();

        $xrayViewer = Item::where('code', '3070106150')->where('order_number', 1)->first();
        $projector = Item::where('code', '3020101101')->where('order_number', 1)->first();

        $data = [];
        if ($xrayViewer && $kp && $maintenanceUpp) {
            $data[] = [
                'user_id' => $kp->id,
                'maintenance_user_id' => $maintenanceUpp->id,
                'item_id' => $xrayViewer->id,
                'item_status' => 'DAMAGED',
                'information' => 'Kecerahan tidak merata, lampu latar berkedip',
                'maintenance_status' => 'PENDING',
                'unit_confirmed' => false,
            ];
        }
        if ($projector && $upa && $maintenanceUmum) {
            $data[] = [
                'user_id' => $upa->id,
                'maintenance_user_id' => $maintenanceUmum->id,
                'item_id' => $projector->id,
                'item_status' => 'GOOD',
                'information' => 'Pemeriksaan jam lampu dan pembersihan filter',
                'maintenance_status' => 'PROCESSING',
                'unit_confirmed' => true,
            ];
        }

        foreach ($data as $row) {
            MaintenanceItemRequest::create($row);
        }
    }
}
