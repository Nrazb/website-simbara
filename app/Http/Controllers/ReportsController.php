<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\{
    ItemRequestExport,
    RemoveItemExport,
    MutationItemExport,
    MaintenanceItemExport,
    ItemsExport
};

class ReportsController extends Controller
{
    public function index ()
    {
        return view('reports.index');
    }

    public function export(Request $request)
    {
        $type  = $request->jenis_laporan;
        $start = $request->start_date;
        $end   = $request->end_date;

        return match ($type) {
            'remove'   => Excel::download(new RemoveItemExport($start, $end), 'laporanPenghapusanBMN.xlsx'),
            'mutation' => Excel::download(new MutationItemExport($start, $end), 'laporanMutasiBMN.xlsx'),
            'maintenance' => Excel::download(new MaintenanceItemExport($start, $end), 'laporanMaintenanceBMN.xlsx'),
            'request'     => Excel::download(new ItemRequestExport($start, $end), 'laporanUsulanBMN.xlsx'),
            'items'       => Excel::download(new ItemsExport($start, $end), 'laporanBarangMilikNegara.xlsx'),
            default       => back()->with('error', 'Jenis laporan tidak ditemukan'),
        };
    }
}
