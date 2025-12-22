<?php

namespace App\Exports;

use App\Models\MutationItemRequest;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class MutationItemExport implements FromCollection, WithHeadings, WithEvents, WithCustomStartCell, WithDrawings
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
            ->with(['item', 'fromUser', 'toUser'])
            ->whereBetween('created_at', [$this->start, $this->end])
            ->get()
            ->map(fn($row) => [
                'ID' => $row->id,
                'Unit'   => $row->item->user->name ?? '-',
                'Nama Item' => $row->item->name ?? '-',
                'Dari User' => $row->fromUser->name ?? '-',
                'Ke User' => $row->toUser->name ?? '-',
                'Konfirmasi Unit' => $row->unit_confirmed ? 'Sudah' : 'Belum',
                'Konfirmasi Unit Penerima' => $row->recipient_confirmed ? 'Sudah' : 'Belum',
                'Tanggal Mutasi Dibuat' => $row->created_at->format('d-m-Y'),
                'Tanggal Mutasi Diperbarui' => $row->updated_at->format('d-m-Y'),
                'Tanggal Mutasi Dihapus' => $row->deleted_at?->format('d-m-Y') ?? '-',
            ]);
    }

    public function headings(): array
    {
        return ['Id', 'Unit', 'Nama Item', 'Dari User', 'Ke User', 'Konfirmasi Unit', 'Konfirmasi Unit Penerima', 'Tanggal Mutasi Dibuat', 'Tanggal Mutasi Diperbarui', 'Tanggal Mutasi Dihapus'];
    }

    public function startCell(): string
    {
        return 'A6';
    }

    protected function titleLabel(): string
    {
        $start = Carbon::parse($this->start)->format('d/m/Y');
        $end   = Carbon::parse($this->end)->format('d/m/Y');
        return "Laporan Mutasi BMN {$start}-{$end}";
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
