<?php

namespace App\Exports;

use App\Models\MaintenanceItemRequest;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MaintenanceItemExport implements FromCollection, WithHeadings
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
        return MaintenanceItemRequest::withTrashed()
            ->with(['user', 'item'])
            ->whereBetween('created_at', [$this->start, $this->end])
            ->get()
            ->map(function ($row) {
                return [
                    'ID'              => $row->id,
                    'Unit'            => $row->user->name ?? '-',
                    'Nama Barang'            => $row->item->name ?? '-',
                    'Status Barang'     => $row->item_status,
                    'Informasi'     => $row->information,
                    'Status Pemeliharaan'  => $row->request_status,
                    'Konfirmasi Unit'  => $row->unit_confirmed ? 'Sudah' : 'Belum',
                    'Tanggal Pemeliharaan Dibuat'  => $row->created_at->format('d-m-Y'),
                    'Tanggal Pemeliharaan Diperbarui' => $row->updated_at->format('d-m-Y'),
                    'Tanggal Pemeliharaan Dihapus'=> $row->deleted_at?->format('d-m-Y') ?? '-',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Unit',
            'Nama Barang',
            'Status Barang',
            'Informasi',
            'Status Pemeliharaan',
            'Konfirmasi Unit',
            'Tanggal Pemeliharaan Dibuat',
            'Tanggal Pemeliharaan Diperbarui',
            'Tanggal Pemeliharaan Dihapus',
        ];
    }
}
