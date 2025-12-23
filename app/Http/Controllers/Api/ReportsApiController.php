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
        $validated = $request->validate([
            'jenis_laporan' => 'required|in:remove,mutation,maintenance,request,items',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $type = $validated['jenis_laporan'];
        $startInput = $validated['start_date'];
        $endInput = $validated['end_date'];

        $start = Carbon::parse($startInput)->startOfDay();
        $end = Carbon::parse($endInput)->endOfDay();

        $startLabel = Carbon::parse($startInput)->format('d-m-Y');
        $endLabel = Carbon::parse($endInput)->format('d-m-Y');

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
