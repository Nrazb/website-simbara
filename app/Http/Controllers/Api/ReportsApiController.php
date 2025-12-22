<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use App\Exports\{
    ItemRequestExport,
    RemoveItemExport,
    MutationItemExport,
    MaintenanceItemExport,
    ItemsExport
};
use App\Http\Controllers\Controller;

class ReportsApiController extends Controller
{
    public function index ()
    {
        return response()->json([
            'available_reports' => ['remove', 'mutation', 'maintenance', 'request', 'items'],
        ]);
    }

    public function export(Request $request)
    {
        $type  = $request->jenis_laporan;
        $start = $request->start_date;
        $end   = $request->end_date;

        $startLabel = Carbon::parse($start)->format('d-m-Y');
        $endLabel   = Carbon::parse($end)->format('d-m-Y');

        return match ($type) {
            'remove'   => Excel::download(new RemoveItemExport($start, $end), "Laporan Penghapusan BMN {$startLabel}-{$endLabel}.xlsx"),
            'mutation' => Excel::download(new MutationItemExport($start, $end), "Laporan Mutasi BMN {$startLabel}-{$endLabel}.xlsx"),
            'maintenance' => Excel::download(new MaintenanceItemExport($start, $end), "Laporan Maintenance BMN {$startLabel}-{$endLabel}.xlsx"),
            'request'     => Excel::download(new ItemRequestExport($start, $end), "Laporan Usulan BMN {$startLabel}-{$endLabel}.xlsx"),
            'items'       => Excel::download(new ItemsExport($start, $end), "Laporan Barang Milik Negara {$startLabel}-{$endLabel}.xlsx"),
            default       => response()->json([
                'message' => 'Jenis laporan tidak ditemukan',
            ], 400),
        };
    }
}
