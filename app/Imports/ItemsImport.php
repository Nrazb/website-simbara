<?php

namespace App\Imports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\ToModel;

class ItemsImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Item([
            'id' => $row[0],
            'user_id' => $row[1],
            'type_id' => $row[2],
            'maintenance_unit_id' => $row[3],
            'code' => $row[4],
            'order_number' => $row[5],
            'name' => $row[6],
            'cost' => $row[7],
            'acquisition_date' => $row[8],
            'acquisition_year' => $row[9],
            'status' => $row[10] ?? 'AVAILABLE',
        ]);
    }
}
