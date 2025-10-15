<?php

namespace Database\Seeders;

use App\Models\MaintenanceItemRequest;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MaintenanceItemRequestSeeder extends Seeder
{
    public function run(): void
    {
        MaintenanceItemRequest::factory(2)->create();
    }
}
