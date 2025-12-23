<?php

namespace App\Exports;

use App\Models\ItemRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class ItemRequestExport implements FromCollection, WithHeadings, WithEvents, WithCustomStartCell, WithDrawings
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
        $query = ItemRequest::withTrashed()
            ->with(['user', 'type'])
            ->whereBetween('created_at', [$this->start, $this->end]);

        $user = Auth::user();
        if ($user->role === 'ADMIN') {
            $query->where(function ($q) use ($user) {
                $q->whereNotNull('sent_at')
                    ->orWhere('user_id', $user->id);
            });
        } else {
            $query->where('user_id', $user->id);
        }

        return $query->get()
            ->map(function ($row) {

                return [
                    'ID'           => $row->id,
                    'User'         => $row->user->name ?? '-',
                    'Tipe Barang'  => $row->type->name ?? '-',
                    'Nama Barang'  => $row->name,
                    'Detail'       => $row->detail,
                    'Qty'          => $row->qty,
                    'Alasan'       => $row->reason,
                    'Tanggal Usulan Dibuat' => $row->created_at->format('d-m-Y'),
                    'Tanggal Usulan Diperbarui' => $row->updated_at->format('d-m-Y'),
                    'Tanggal Usulan Dihapus' => $row->deleted_at?->format('d-m-Y') ?? '-',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Id',
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

    public function startCell(): string
    {
        return 'A6';
    }

    protected function titleLabel(): string
    {
        $start = Carbon::parse($this->start)->format('d/m/Y');
        $end   = Carbon::parse($this->end)->format('d/m/Y');
        return "Laporan Usulan BMN {$start}-{$end}";
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $colCount = count($this->headings());
                $lastCol = Coordinate::stringFromColumnIndex($colCount);

                $sheet->mergeCells("A3:{$lastCol}3");
                $sheet->setCellValue('A3', $this->titleLabel());
                $sheet->getStyle("A3")->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle("A3")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                $period = Carbon::parse($this->start)->format('d/m/Y') . ' - ' . Carbon::parse($this->end)->format('d/m/Y');
                $sheet->mergeCells("A4:{$lastCol}4");
                $sheet->setCellValue('A4', "Periode: {$period}");
                $sheet->getStyle("A4")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                $sheet->mergeCells("A5:{$lastCol}5");
                $sheet->setCellValue('A5', 'Politeknik Negeri Indramayu');
                $sheet->getStyle("A5")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                $headerRange = "A6:{$lastCol}6";
                $sheet->getStyle($headerRange)->getFont()->setBold(true);
                $sheet->getStyle($headerRange)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('DDEAFE');
                $sheet->getStyle($headerRange)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }
        ];
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Polindra');
        $drawing->setPath(public_path('images/polindra.png'));
        $drawing->setHeight(80);
        $drawing->setCoordinates('B2');
        return [$drawing];
    }
}
