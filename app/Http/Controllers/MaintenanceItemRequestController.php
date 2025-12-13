<?php

namespace App\Http\Controllers;

use App\Repositories\MaintenanceItemRequestRepositoryInterface;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use App\Models\MaintenanceItemRequest;

class MaintenanceItemRequestController extends Controller
{
    protected $maintenanceItemRequestRepository;

    public function __construct(MaintenanceItemRequestRepositoryInterface $maintenanceItemRequestRepository)
    {
        $this->maintenanceItemRequestRepository = $maintenanceItemRequestRepository;
    }

    public function index(Request $request)
    {
        $maintenanceItemRequests = $this->maintenanceItemRequestRepository->all();
        return view('maintenance_item_requests.index', compact('maintenanceItemRequests'));
    }

    public function confirmUnit(MaintenanceItemRequest $maintenanceItemRequest)
    {
        $ownerId = $maintenanceItemRequest->user_id;
        $user = Auth::user();
        try {
            if ($user->id !== $ownerId) {
                return back()->with('error', 'Akses ditolak: hanya pemilik data yang dapat mengkonfirmasi unit.');
            }
            if ($maintenanceItemRequest->unit_confirmed) {
                return back()->with('success', 'Unit sudah dikonfirmasi.');
            }
            $this->maintenanceItemRequestRepository->update($maintenanceItemRequest->id, ['unit_confirmed' => true]);
            return back()->with('success', 'Konfirmasi unit berhasil.');
        } catch (QueryException $e) {
            return back()->with('error', 'Kesalahan database saat memproses tindakan.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal memproses tindakan.');
        }
    }

    public function updateRequestStatus(Request $request, MaintenanceItemRequest $maintenanceItemRequest)
    {
        $user = Auth::user();
        try {
            if ($user->role !== 'MAINTENANCE_UNIT') {
                return back()->with('error', 'Akses ditolak: hanya unit pemeliharaan yang dapat memperbarui status pemeliharaan.');
            }
            if ($maintenanceItemRequest->unit_confirmed) {
                return back()->with('error', 'Perubahan tidak diizinkan setelah konfirmasi unit.');
            }
            $value = $request->input('value');
            $allowed = ['PENDING', 'PROCESS', 'COMPLETED', 'REJECTED', 'REMOVED'];
            if (!in_array($value, $allowed, true)) {
                return back()->with('error', 'Status pemeliharaan tidak valid.');
            }
            $this->maintenanceItemRequestRepository->update($maintenanceItemRequest->id, ['request_status' => $value]);
            return back()->with('success', 'Status pemeliharaan diperbarui.');
        } catch (QueryException $e) {
            return back()->with('error', 'Kesalahan database saat memproses tindakan.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal memproses tindakan.');
        }
    }

    public function updateItemStatus(Request $request, MaintenanceItemRequest $maintenanceItemRequest)
    {
        $user = Auth::user();
        try {
            if ($user->role !== 'MAINTENANCE_UNIT') {
                return back()->with('error', 'Akses ditolak: hanya unit pemeliharaan yang dapat memperbarui status barang.');
            }
            if ($maintenanceItemRequest->unit_confirmed) {
                return back()->with('error', 'Perubahan tidak diizinkan setelah konfirmasi unit.');
            }
            $value = $request->input('value');
            $allowed = ['GOOD', 'DAMAGED', 'REPAIRED'];
            if (!in_array($value, $allowed, true)) {
                return back()->with('error', 'Status barang tidak valid.');
            }
            $this->maintenanceItemRequestRepository->update($maintenanceItemRequest->id, ['item_status' => $value]);
            return back()->with('success', 'Status barang diperbarui.');
        } catch (QueryException $e) {
            return back()->with('error', 'Kesalahan database saat memproses tindakan.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal memproses tindakan.');
        }
    }
}
