<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMaintenanceItemRequest;
use App\Http\Resources\MaintenanceItemRequestResource;
use App\Http\Resources\UserResource;
use App\Models\MaintenanceItemRequest;
use App\Models\User;
use App\Repositories\MaintenanceItemRequestRepositoryInterface;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Throwable;

class MaintenanceItemRequestApiController extends Controller
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

        return MaintenanceItemRequestResource::collection($maintenanceItemRequests)->additional([
            'maintenance_units' => UserResource::collection($maintenanceUnits),
        ]);
    }

    public function store(StoreMaintenanceItemRequest $request)
    {
        $validated = $request->validated();
        try {
            $maintenanceItemRequest = $this->maintenanceItemRequestRepository->create($validated);
            $maintenanceItemRequest->load(['user', 'item']);

            return (new MaintenanceItemRequestResource($maintenanceItemRequest))
                ->additional(['message' => 'Permintaan pemeliharaan dibuat.'])
                ->response()
                ->setStatusCode(201);
        } catch (QueryException $e) {
            $code = $e->getCode();
            Log::error("Database error: $code");
            $msg = $code == 23000 ? 'Data duplikat atau referensi tidak valid.' : 'Kesalahan database. Coba lagi.';

            return response()->json([
                'message' => $msg,
            ], 500);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Gagal membuat permintaan pemeliharaan.',
            ], 500);
        }
    }

    public function confirmUnit(MaintenanceItemRequest $maintenanceItemRequest)
    {
        $ownerId = $maintenanceItemRequest->user_id;
        $user = Auth::user();
        try {
            if ($user->id !== $ownerId) {
                return response()->json([
                    'message' => 'Akses ditolak: hanya pemilik data yang dapat mengkonfirmasi unit.',
                ], 403);
            }
            if ($maintenanceItemRequest->unit_confirmed) {
                $maintenanceItemRequest->load(['user', 'item']);

                return (new MaintenanceItemRequestResource($maintenanceItemRequest))
                    ->additional(['message' => 'Unit sudah dikonfirmasi.'])
                    ->response();
            }
            $this->maintenanceItemRequestRepository->update($maintenanceItemRequest->id, ['unit_confirmed' => true]);
            $maintenanceItemRequest->refresh()->load(['user', 'item']);

            return (new MaintenanceItemRequestResource($maintenanceItemRequest))
                ->additional(['message' => 'Konfirmasi unit berhasil.'])
                ->response();
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Kesalahan database saat memproses tindakan.',
            ], 500);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Gagal memproses tindakan.',
            ], 500);
        }
    }

    public function updateRequestStatus(Request $request, MaintenanceItemRequest $maintenanceItemRequest)
    {
        $user = Auth::user();
        try {
            if ($maintenanceItemRequest->unit_confirmed) {
                return response()->json([
                    'message' => 'Perubahan tidak diizinkan setelah konfirmasi unit.',
                ], 400);
            }

            $current = $maintenanceItemRequest->maintenance_status;
            $value = $request->input('value');
            $allowed = ['PENDING', 'APPROVED', 'BEING_SENT', 'PROCESSING', 'COMPLETED', 'REJECTED', 'REMOVED', 'BEING_SENT_BACK'];
            if (! in_array($value, $allowed, true)) {
                return response()->json([
                    'message' => 'Status pemeliharaan tidak valid.',
                ], 400);
            }

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

            if (! $canUpdate) {
                return response()->json([
                    'message' => 'Anda tidak diizinkan mengubah status ke tahap ini.',
                ], 403);
            }

            $this->maintenanceItemRequestRepository->update($maintenanceItemRequest->id, ['maintenance_status' => $value]);
            $maintenanceItemRequest->refresh()->load(['user', 'item']);

            return (new MaintenanceItemRequestResource($maintenanceItemRequest))
                ->additional(['message' => 'Status pemeliharaan diperbarui.'])
                ->response();
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Kesalahan database saat memproses tindakan.',
            ], 500);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Gagal memproses tindakan.',
            ], 500);
        }
    }

    public function updateItemStatus(Request $request, MaintenanceItemRequest $maintenanceItemRequest)
    {
        $user = Auth::user();
        try {
            if ($user->role !== 'MAINTENANCE_UNIT') {
                return response()->json([
                    'message' => 'Akses ditolak: hanya unit pemeliharaan yang dapat memperbarui status barang.',
                ], 403);
            }
            if ($maintenanceItemRequest->unit_confirmed) {
                return response()->json([
                    'message' => 'Perubahan tidak diizinkan setelah konfirmasi unit.',
                ], 400);
            }
            $value = $request->input('value');
            $allowed = ['GOOD', 'DAMAGED', 'REPAIRED'];
            if (! in_array($value, $allowed, true)) {
                return response()->json([
                    'message' => 'Status barang tidak valid.',
                ], 400);
            }
            $this->maintenanceItemRequestRepository->update($maintenanceItemRequest->id, ['item_status' => $value]);
            $maintenanceItemRequest->refresh()->load(['user', 'item']);

            return (new MaintenanceItemRequestResource($maintenanceItemRequest))
                ->additional(['message' => 'Status barang diperbarui.'])
                ->response();
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Kesalahan database saat memproses tindakan.',
            ], 500);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Gagal memproses tindakan.',
            ], 500);
        }
    }

    public function updateInformation(Request $request, MaintenanceItemRequest $maintenanceItemRequest)
    {
        $user = Auth::user();
        try {
            if ($user->role !== 'MAINTENANCE_UNIT') {
                return response()->json([
                    'message' => 'Akses ditolak: hanya unit pemeliharaan yang dapat memperbarui informasi.',
                ], 403);
            }
            if ($maintenanceItemRequest->unit_confirmed) {
                return response()->json([
                    'message' => 'Perubahan tidak diizinkan setelah konfirmasi unit.',
                ], 400);
            }
            $information = (string) $request->input('information', '');
            $this->maintenanceItemRequestRepository->update($maintenanceItemRequest->id, ['information' => $information]);
            $maintenanceItemRequest->refresh()->load(['user', 'item']);

            return (new MaintenanceItemRequestResource($maintenanceItemRequest))
                ->additional(['message' => 'Informasi pemeliharaan diperbarui.'])
                ->response();
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Kesalahan database saat memproses tindakan.',
            ], 500);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Gagal memproses tindakan.',
            ], 500);
        }
    }
}
