<?php

namespace Database\Seeders;

use App\Models\Type;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypeSeeder extends Seeder
{
    public function run(): void
    {
        $names = [
            'Peralatan Laboratorium',
            'Perabotan Rumah Sakit',
            'Peralatan Pencitraan',
            'Peralatan TI',
            'Peralatan Jaringan',
            'Peralatan Kantor',
            'Perangkat Medis',
        ];

        foreach ($names as $name) {
            Type::firstOrCreate(['name' => $name]);
        }
    }
}
