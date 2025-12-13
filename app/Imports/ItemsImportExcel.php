<?php

namespace App\Imports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ItemsImportExcel implements ToModel, WithHeadingRow, WithStartRow
{
    public function headingRow(): int
    {
        return 2;
    }

    public function startRow(): int
    {
        return 3;
    }

    public function model(array $row)
    {
        return new Item([
            'id' => $row['id'] ?? null,
            'user_id' => $row['user_id'] ?? null,
            'type_id' => $row['type_id'] ?? null,
            'maintenance_unit_id' => $row['maintenance_unit_id'] ?? null,
            'code' => $row['code'] ?? null,
            'order_number' => $row['order_number'] ?? null,
            'name' => $row['name'] ?? null,
            'cost' => $row['cost'] ?? null,
            'acquisition_date' => $row['acquisition_date'] ?? null,
            'acquisition_year' => $row['acquisition_year'] ?? null,
            'status' => $row['status'] ?? 'AVAILABLE',
        ]);
    }
}

