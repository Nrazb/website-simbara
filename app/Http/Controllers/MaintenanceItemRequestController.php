<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMaintenanceItemRequest;
use App\Models\User;
use App\Repositories\MaintenanceItemRequestRepositoryInterface;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MaintenanceItemRequest;
use Illuminate\Support\Facades\Log;
use Throwable;

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
        $maintenanceUnits = User::where('role', 'MAINTENANCE_UNIT')->orderBy('name')->get();
        return view('maintenance_item_requests.index', compact('maintenanceItemRequests', 'maintenanceUnits'));
    }

    public function store(StoreMaintenanceItemRequest $request)
    {
        $validated = $request->validated();
        try {
            $this->maintenanceItemRequestRepository->create($validated);
            return redirect()->route('items.index')->with('success', 'Permintaan pemeliharaan dibuat.');
        } catch (QueryException $e) {
            $code = $e->getCode();
            Log::error("Database error: $code");
            $msg = $code == 23000 ? 'Data duplikat atau referensi tidak valid.' : 'Kesalahan database. Coba lagi.';
            return redirect()->back()->withInput()->with('error', $msg);
        } catch (Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal membuat permintaan pemeliharaan.');
        }
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
        } catch (Throwable $e) {
            return back()->with('error', 'Gagal memproses tindakan.');
        }
    }

    public function updateRequestStatus(Request $request, MaintenanceItemRequest $maintenanceItemRequest)
    {
        $user = Auth::user();
        try {
            if ($maintenanceItemRequest->unit_confirmed) {
                return back()->with('error', 'Perubahan tidak diizinkan setelah konfirmasi unit.');
            }

            $current = $maintenanceItemRequest->maintenance_status;
            $value = $request->input('value');
            $allowed = ['PENDING', 'APPROVED', 'BEING_SENT', 'PROCESSING', 'COMPLETED', 'REJECTED', 'REMOVED', 'BEING_SENT_BACK'];
            if (!in_array($value, $allowed, true)) {
                return back()->with('error', 'Status pemeliharaan tidak valid.');
            }

            // Flow rules
            $canUpdate = false;
            if ($current === 'PENDING' && in_array($value, ['APPROVED', 'REJECTED'], true) && $user->role === 'MAINTENANCE_UNIT') {
                $canUpdate = true;
            }
            if ($current === 'APPROVED' && $value === 'BEING_SENT' && $user->id === $maintenanceItemRequest->user_id) {
                $canUpdate = true;
            }
            if ($current === 'BEING_SENT' && in_array($value, ['PROCESSING', 'BEING_SENT_BACK'], true) && $user->id === $maintenanceItemRequest->user_id) {
                $canUpdate = true;
            }
            if (in_array($current, ['PROCESSING', 'BEING_SENT_BACK'], true) && in_array($value, ['COMPLETED', 'REJECTED', 'REMOVED'], true) && $user->role === 'MAINTENANCE_UNIT') {
                $canUpdate = true;
            }

            if (!$canUpdate) {
                return back()->with('error', 'Anda tidak diizinkan mengubah status ke tahap ini.');
            }

            $this->maintenanceItemRequestRepository->update($maintenanceItemRequest->id, ['maintenance_status' => $value]);
            return back()->with('success', 'Status pemeliharaan diperbarui.');
        } catch (QueryException $e) {
            return back()->with('error', 'Kesalahan database saat memproses tindakan.');
        } catch (Throwable $e) {
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
        } catch (Throwable $e) {
            return back()->with('error', 'Gagal memproses tindakan.');
        }
    }

    public function updateInformation(Request $request, MaintenanceItemRequest $maintenanceItemRequest)
    {
        $user = Auth::user();
        try {
            if ($user->role !== 'MAINTENANCE_UNIT') {
                return back()->with('error', 'Akses ditolak: hanya unit pemeliharaan yang dapat memperbarui informasi.');
            }
            if ($maintenanceItemRequest->unit_confirmed) {
                return back()->with('error', 'Perubahan tidak diizinkan setelah konfirmasi unit.');
            }
            $information = (string) $request->input('information', '');
            $this->maintenanceItemRequestRepository->update($maintenanceItemRequest->id, ['information' => $information]);
            return back()->with('success', 'Informasi pemeliharaan diperbarui.');
        } catch (QueryException $e) {
            return back()->with('error', 'Kesalahan database saat memproses tindakan.');
        } catch (Throwable $e) {
            return back()->with('error', 'Gagal memproses tindakan.');
        }
    }
}
