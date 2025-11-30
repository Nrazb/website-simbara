<?php

namespace App\Exports;

use App\Models\ItemRequest;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ItemRequestExport implements FromCollection, WithHeadings
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
        return ItemRequest::withTrashed()
            ->with(['user', 'type'])
            ->whereBetween('created_at', [$this->start, $this->end])
            ->get()
            ->map(function ($row) {

                return [
                    'ID'           => $row->id,
                    'User'         => $row->user->name ?? '-',
                    'Tipe Barang'  => $row->type->name ?? '-',
                    'Nama Barang'  => $row->name,
                    'Detail'       => $row->detail,
                    'Qty'          => $row->qty,
                    'Alasan'       => $row->reason,
                    'Tanggal Usulan Dibuat'=> $row->created_at->format('d-m-Y'),
                    'Tanggal Usulan Diperbarui'=> $row->updated_at->format('d-m-Y'),
                    'Tanggal Usulan Dihapus'=> $row->deleted_at?->format('d-m-Y') ?? '-',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'ID',
            'User',
            'Tipe Barang',
            'Nama Barang',
            'Detail',
            'Qty',
            'Alasan',
            'Tanggal Usulan Dibuat',
            'Tanggal Usulan Diperbarui',
            'Tanggal Usulan Dihapus',
        ];
    }
}
