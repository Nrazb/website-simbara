<?php

namespace Database\Seeders;

use App\Models\RemoveItemRequest;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RemoveItemRequestSeeder extends Seeder
{
    public function run(): void
    {
        RemoveItemRequest::factory(2)->create();
    }
}
