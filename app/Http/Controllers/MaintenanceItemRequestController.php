<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMaintenanceItemRequest;
use App\Http\Requests\UpdateMaintenanceItemRequest;
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

            $status = $maintenanceItemRequest->maintenance_status;
            if (! in_array($status, ['REJECTED', 'REMOVED', 'COMPLETED', 'BEING_RECEIVED_BACK'], true)) {
                return back()->with('error', 'Konfirmasi hanya dapat dilakukan pada status tertentu.');
            }

            $payload = ['unit_confirmed' => true];
            if ($status === 'BEING_RECEIVED_BACK') {
                $payload['maintenance_status'] = 'COMPLETED';
            }

            $this->maintenanceItemRequestRepository->update($maintenanceItemRequest->id, $payload);
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
            $allowed = [
                'PENDING',
                'APPROVED',
                'BEING_SENT',
                'BEING_RECEIVED',
                'PROCESSING',
                'FIINISHED',
                'REJECTED',
                'REMOVED',
                'BEING_SENT_BACK',
                'BEING_RECEIVED_BACK',
                'COMPLETED',
            ];
            if (!in_array($value, $allowed, true)) {
                return back()->with('error', 'Status pemeliharaan tidak valid.');
            }

            // Flow rules
            $canUpdate = false;

            $isMaintenanceUnit = $user->role === 'MAINTENANCE_UNIT';
            $isOwner = $user->id === $maintenanceItemRequest->user_id && $user->role !== 'MAINTENANCE_UNIT';

            if ($isMaintenanceUnit && (int) $maintenanceItemRequest->maintenance_user_id !== (int) $user->id) {
                return back()->with('error', 'Akses ditolak: hanya unit pemeliharaan yang ditugaskan dapat mengubah status.');
            }

            if ($current === 'PENDING' && in_array($value, ['APPROVED', 'REJECTED'], true) && $isMaintenanceUnit) {
                $canUpdate = true;
            } elseif ($current === 'PENDING' && $value === 'BEING_SENT' && $isOwner) {
                $canUpdate = true;
            } elseif ($current === 'APPROVED' && $value === 'BEING_SENT' && ($isOwner || $isMaintenanceUnit)) {
                $canUpdate = true;
            } elseif ($current === 'BEING_SENT' && $value === 'BEING_RECEIVED' && $isMaintenanceUnit) {
                $canUpdate = true;
            } elseif ($current === 'BEING_RECEIVED' && $value === 'PROCESSING' && $isMaintenanceUnit) {
                $canUpdate = true;
            } elseif ($current === 'PROCESSING' && in_array($value, ['FIINISHED', 'REJECTED', 'REMOVED'], true) && $isMaintenanceUnit) {
                $canUpdate = true;
            } elseif (in_array($current, ['FIINISHED', 'REJECTED'], true) && $value === 'BEING_SENT_BACK' && $isMaintenanceUnit) {
                $canUpdate = true;
            } elseif ($current === 'BEING_SENT_BACK' && $value === 'BEING_RECEIVED_BACK' && $isOwner) {
                $canUpdate = true;
            } elseif ($current === 'BEING_RECEIVED_BACK' && $value === 'COMPLETED' && $isOwner) {
                $canUpdate = true;
            }

            if (!$canUpdate) {
                return back()->with('error', 'Anda tidak diizinkan mengubah status ke tahap ini.');
            }

            $maintenanceItemRequest->maintenance_status = $value;
            $maintenanceItemRequest->save();
            $maintenanceItemRequest->refresh();

            if ($maintenanceItemRequest->maintenance_status !== $value) {
                return back()->with('error', 'Status pemeliharaan gagal diperbarui.');
            }

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
            if ($maintenanceItemRequest->maintenance_status !== 'PROCESSING') {
                return back()->with('error', 'Status barang hanya dapat diperbarui saat tahap diproses.');
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

    public function updateInformation(UpdateMaintenanceItemRequest $request, MaintenanceItemRequest $maintenanceItemRequest)
    {
        $user = Auth::user();
        try {
            if ($user->role !== 'MAINTENANCE_UNIT') {
                return back()->with('error', 'Akses ditolak: hanya unit pemeliharaan yang dapat memperbarui informasi.');
            }
            if ($maintenanceItemRequest->unit_confirmed) {
                return back()->with('error', 'Perubahan tidak diizinkan setelah konfirmasi unit.');
            }

            if (! in_array($maintenanceItemRequest->maintenance_status, ['COMPLETED', 'REJECTED', 'REMOVED', 'FIINISHED'], true)) {
                return back()->with('error', 'Informasi hanya dapat diubah pada status tertentu.');
            }

            $validated = $request->validated();
            $information = (string) ($validated['information'] ?? '');
            $this->maintenanceItemRequestRepository->update($maintenanceItemRequest->id, ['information' => $information]);
            return back()->with('success', 'Informasi pemeliharaan diperbarui.');
        } catch (QueryException $e) {
            return back()->with('error', 'Kesalahan database saat memproses tindakan.');
        } catch (Throwable $e) {
            return back()->with('error', 'Gagal memproses tindakan.');
        }
    }
}
