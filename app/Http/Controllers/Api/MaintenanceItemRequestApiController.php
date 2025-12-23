<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMaintenanceItemRequest;
use App\Http\Requests\UpdateMaintenanceItemRequest;
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

            $status = $maintenanceItemRequest->maintenance_status;
            if (! in_array($status, ['REJECTED', 'REMOVED', 'COMPLETED', 'BEING_RECEIVED_BACK'], true)) {
                return response()->json([
                    'message' => 'Konfirmasi hanya dapat dilakukan pada status tertentu.',
                ], 400);
            }

            $payload = ['unit_confirmed' => true];
            if ($status === 'BEING_RECEIVED_BACK') {
                $payload['maintenance_status'] = 'COMPLETED';
            }

            $this->maintenanceItemRequestRepository->update($maintenanceItemRequest->id, $payload);
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
            if (! in_array($value, $allowed, true)) {
                return response()->json([
                    'message' => 'Status pemeliharaan tidak valid.',
                ], 400);
            }

            $canUpdate = false;

            $isMaintenanceUnit = $user->role === 'MAINTENANCE_UNIT';
            $isOwner = $user->id === $maintenanceItemRequest->user_id && $user->role !== 'MAINTENANCE_UNIT';

            if ($isMaintenanceUnit && (int) $maintenanceItemRequest->maintenance_user_id !== (int) $user->id) {
                return response()->json([
                    'message' => 'Akses ditolak: hanya unit pemeliharaan yang ditugaskan dapat mengubah status.',
                ], 403);
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

            if (! $canUpdate) {
                return response()->json([
                    'message' => 'Anda tidak diizinkan mengubah status ke tahap ini.',
                ], 403);
            }

            $maintenanceItemRequest->maintenance_status = $value;
            $maintenanceItemRequest->save();
            $maintenanceItemRequest->refresh()->load(['user', 'item']);

            if ($maintenanceItemRequest->maintenance_status !== $value) {
                return response()->json([
                    'message' => 'Status pemeliharaan gagal diperbarui.',
                ], 500);
            }

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
            if ($maintenanceItemRequest->maintenance_status !== 'PROCESSING') {
                return response()->json([
                    'message' => 'Status barang hanya dapat diperbarui saat tahap diproses.',
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

    public function updateInformation(UpdateMaintenanceItemRequest $request, MaintenanceItemRequest $maintenanceItemRequest)
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

            if (! in_array($maintenanceItemRequest->maintenance_status, ['COMPLETED', 'REJECTED', 'REMOVED', 'FIINISHED'], true)) {
                return response()->json([
                    'message' => 'Informasi hanya dapat diubah pada status tertentu.',
                ], 400);
            }

            $validated = $request->validated();
            $information = (string) ($validated['information'] ?? '');
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
