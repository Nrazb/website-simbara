<?php

namespace App\Exports;

use App\Models\MutationItemRequest;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MutationItemExport implements FromCollection, WithHeadings
{
    protected $start;
    protected $end;

    public function __construct($start, $end)
    {
        $this->start = $start;
        $this->end = $end;
    }

    public function collection()
    {
        return MutationItemRequest::withTrashed()
            ->with(['item', 'fromUser', 'toUser', 'maintenanceUnit'])
            ->whereBetween('created_at', [$this->start, $this->end])
            ->get()
            ->map(fn ($row) => [
                'ID' => $row->id,
                'Unit Pemeliharaan'   => $row->maintenanceUnit->name ?? '-',
                'Nama Item' => $row->item->name ?? '-',
                'Dari User' => $row->fromUser->name ?? '-',
                'Ke User' => $row->toUser->name ?? '-',
                'Konfirmasi Unit'=> $row->unit_confirmed ? 'Sudah' : 'Belum',
                'Konfirmasi Unit Penerima'=> $row->recipient_confirmed ? 'Sudah' : 'Belum',
                'Tanggal Mutasi Dibuat' => $row->created_at->format('d-m-Y'),
                'Tanggal Mutasi Diperbarui' => $row->updated_at->format('d-m-Y'),
                'Tanggal Mutasi Dihapus'=> $row->deleted_at?->format('d-m-Y') ?? '-',
            ]);
    }

    public function headings(): array
    {
        return ['ID', 'Unit Pemeliharaan', 'Nama Item', 'Dari User', 'Ke User', 'Konfirmasi Unit', 'Konfirmasi Unit Penerima', 'Tanggal Mutasi Dibuat', 'Tanggal Mutasi Diperbarui', 'Tanggal Mutasi Dihapus'];
    }
}

