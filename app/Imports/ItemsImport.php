<?php

namespace App\Imports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ItemsImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Item([
            'id' => $row['id'],
            'user_id' => $row['user_id'],
            'type_id' => $row['type_id'],
            'code' => $row['code'],
            'order_number' => $row['order_number'],
            'name' => $row['name'],
            'cost' => $row['cost'],
            'acquisition_date' => $row['acquisition_date'],
            'acquisition_year' => $row['acquisition_year'],
            'status' => $row['status'],
        ]);
    }
}
