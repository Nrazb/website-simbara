<?php

namespace App\Exports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ItemsExport implements FromCollection, WithHeadings
{
    protected $start;
    protected $end;

    public function __construct($start, $end)
    {
        $this->start = $start;
        $this->end   = $end;
    }

    public function collection()
    {
        return Item::withTrashed()
            ->with(['user', 'type', 'maintenanceUnit'])
            ->whereBetween('created_at', [$this->start, $this->end])
            ->get()
            ->map(function ($row) {
                return [
                    'ITEM ID'               => $row->id,
                    'USER ID'               => $row->user->name ?? '-',
                    'TYPE ID'               => $row->type->name ?? '-',
                    'MAINTENANCE UNIT ID'   => $row->maintenanceUnit->name ?? '-',
                    'CODE'                  => $row->code,
                    'NUP'                   => $row->order_number,
                    'ITEM NAME'             => $row->name,
                    'COST'                  => $row->cost,
                    'ACQUISITION DATE'      => $row->acquisition_date,
                    'ACQUISITION YEAR'      => $row->acquisition_year,
                    'STATUS ITEM'           => $row->status,
                    'CREATED AT'            => $row->created_at?->format('d-m-Y'),
                    'UPDATED AT'            => $row->updated_at?->format('d-m-Y'),
                    'DELETED AT'            => $row->deleted_at?->format('d-m-Y') ?? '-',
                ];
            });
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
