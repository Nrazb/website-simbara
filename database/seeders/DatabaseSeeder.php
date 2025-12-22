<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\TypeSeeder;
use Database\Seeders\ItemSeeder;
use Database\Seeders\ItemRequestSeeder;
use Database\Seeders\MutationItemRequestSeeder;
use Database\Seeders\MaintenanceItemRequestSeeder;
use Database\Seeders\RemoveItemRequestSeeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = [
            [
                'code' => 'DIREKTORAT',
                'name' => 'Direktorat',
                'password' => Hash::make('password'),
                'role' => 'ADMIN',
                'can_borrow' => false,
            ],
            [
                'code' => 'UPATIK',
                'name' => 'UPA TIK',
                'password' => Hash::make('password'),
                'role' => 'UNIT',
                'can_borrow' => false,
            ],
            [
                'code' => 'UPP',
                'name' => 'UPP',
                'password' => Hash::make('password'),
                'role' => 'MAINTENANCE_UNIT',
                'can_borrow' => false,
            ],
            [
                'code' => 'UMUM',
                'name' => 'Umum',
                'password' => Hash::make('password'),
                'role' => 'MAINTENANCE_UNIT',
                'can_borrow' => false,
            ],
            [
                'code' => 'RPLUNIT',
                'name' => 'RPL Unit',
                'password' => Hash::make('password'),
                'role' => 'UNIT',
                'can_borrow' => true,
            ],
            [
                'code' => 'TIUNIT',
                'name' => 'TI Unit',
                'password' => Hash::make('password'),
                'role' => 'UNIT',
                'can_borrow' => true,
            ],
            [
                'code' => 'KEPERAWATAN',
                'name' => 'KP Unit',
                'password' => Hash::make('password'),
                'role' => 'UNIT',
                'can_borrow' => true,
            ],
        ];

        User::insert($users);

        $this->call([
            TypeSeeder::class,
            ItemSeeder::class,
            ItemRequestSeeder::class,
            MutationItemRequestSeeder::class,
            MaintenanceItemRequestSeeder::class,
            RemoveItemRequestSeeder::class,
        ]);
    }
}
