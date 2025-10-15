<?php

namespace Database\Seeders;

use App\Models\MutationItemRequest;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MutationItemRequestSeeder extends Seeder
{
    public function run(): void
    {
        MutationItemRequest::factory(2)->create();
    }
}
