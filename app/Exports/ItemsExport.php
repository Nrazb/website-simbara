<?php

namespace App\Exports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ItemsExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Item::select(
            'id',
            'user_id',
            'type_id',
            'maintenance_unit_id',
            'code',
            'order_number',
            'name',
            'cost',
            'acquisition_date',
            'acquisition_year',
            'status',
            'created_at',
            'updated_at',
            'deleted_at'
        )->get();
    }

    public function headings(): array
    {
        return [
            'ITEM ID',
            'USER ID',
            'TYPE ID',
            'MAINTENANCE UNIT ID',
            'CODE',
            'NUP',
            'ITEM NAME',
            'COST',
            'ACQUISITION DATE',
            'ACQUISITION YEAR',
            'STATUS ITEM',
            'CREATED AT',
            'UPDATED AT',
            'DELETED AT'
        ];
    }
}
