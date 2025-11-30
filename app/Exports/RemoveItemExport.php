<?php
namespace App\Exports;

use App\Models\RemoveItemRequest;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RemoveItemExport implements FromCollection, WithHeadings
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
        return RemoveItemRequest::withTrashed()
            ->with(['item', 'user'])
            ->whereBetween('created_at', [$this->start, $this->end])
            ->get()
            ->map(function ($row) {
                return [
                    'ID'            => $row->id,
                    'Nama Item'     => $row->item->name ?? '-',
                    'User'          => $row->user->name ?? '-',
                    'Status'        => $row->status,
                    'Unit Confirmed'=> $row->unit_confirmed ? 'Ya' : 'Tidak',
                    'Tanggal Dibuat'=> $row->created_at->format('d-m-Y'),
                    'Tanggal Diubah'=> $row->updated_at->format('d-m-Y'),
                    'Tanggal Dihapus'=> $row->deleted_at?->format('d-m-Y') ?? '-',
                ];
            });
    }

    public function headings(): array
    {
        return ['ID', 'Nama Item', 'User', 'Status', 'Konfirmasi Unit', 'Tanggal Dibuat', 'Tanggal Diubah', 'Tanggal Dihapus'];
    }
}
