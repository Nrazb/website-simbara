<?php

namespace Database\Seeders;

use App\Models\ItemRequest;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemRequestSeeder extends Seeder
{
    public function run(): void
    {
        ItemRequest::factory(2)->create();
    }
}
