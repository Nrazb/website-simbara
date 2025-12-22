<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ImportItemsRequest;
use App\Http\Requests\StoreItemRequestForm;
use App\Http\Resources\ItemRequestResource;
use App\Http\Resources\ItemResource;
use App\Http\Resources\TypeResource;
use App\Http\Resources\UserResource;
use App\Imports\ItemsImport;
use App\Imports\ItemsImportExcel;
use App\Models\Item;
use App\Models\ItemRequest;
use App\Models\User;
use App\Repositories\ItemRepositoryInterface;
use App\Repositories\TypeRepositoryInterface;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;
use PhpOffice\PhpSpreadsheet\Reader\Exception as PhpSpreadsheetReaderException;

class ItemApiController extends Controller
{
    protected $itemRepository;

    protected $typeRepository;

    public function __construct(ItemRepositoryInterface $itemRepository, TypeRepositoryInterface $typeRepository)
    {
        $this->itemRepository = $itemRepository;
        $this->typeRepository = $typeRepository;
    }

    public function index(Request $request)
    {
        $types = $this->typeRepository->all();
        $search = $request->input('search');
        $perPage = $request->input('per_page', 5);

        $users = User::whereIn('id', function ($query) {
            $query->select('user_id')->from('items');
        })
            ->orderBy('name')
            ->get();
        $years = Item::selectRaw('acquisition_year as year')
            ->when(Auth::user()->role !== 'ADMIN', function ($q) {
                $q->where('user_id', Auth::id());
            })
            ->distinct()
            ->orderBy('year', 'desc')
            ->get();
        $filters = [
            'user_id' => $request->input('user_id'),
            'year' => $request->input('year'),
        ];
        $items = $this->itemRepository->all($search, $perPage, $filters);
        $unitsSelect = User::where('role', 'UNIT')->orderBy('name')->get();
        $maintenanceUnits = User::where('role', 'MAINTENANCE_UNIT')->orderBy('name')->get();

        return ItemResource::collection($items)->additional([
            'types' => TypeResource::collection($types),
            'users' => UserResource::collection($users),
            'years' => $years,
            'units' => UserResource::collection($unitsSelect),
            'maintenance_units' => UserResource::collection($maintenanceUnits),
        ]);
    }

    public function create()
    {
        $types = $this->typeRepository->all();
        $itemRequests = ItemRequest::where('qty', '>', 0)->with('type')
            ->when(Auth::user()->role !== 'ADMIN', function ($q) {
                $q->where('user_id', Auth::user()->id);
            })
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'types' => TypeResource::collection($types),
            'item_requests' => ItemRequestResource::collection($itemRequests),
        ]);
    }

    public function store(StoreItemRequestForm $request, ItemRequest $itemRequest)
    {
        $validated = $request->validated();
        $datas = [];
        $createdIds = [];
        try {
            $createdCount = (int) $validated['quantity'];

            DB::transaction(function () use ($validated, $createdCount, &$datas, &$createdIds, $itemRequest) {
                for ($i = 1; $i <= $createdCount; $i++) {
                    $id = $validated['code'] . '-' . $i;
                    $createdIds[] = $id;
                    $datas[] = [
                        'id' => $id,
                        'order_number' => $i,
                        'user_id' => $validated['user_id'],
                        'type_id' => $validated['type_id'],
                        'code' => $validated['code'],
                        'name' => $validated['name'],
                        'cost' => $validated['cost'],
                        'acquisition_date' => $validated['acquisition_date'],
                        'acquisition_year' => $validated['acquisition_year'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                $this->itemRepository->create($datas);
                $itemRequest->decrement('qty', $createdCount);
            });

            $itemsQuery = Item::query()->whereIn('id', $createdIds)->with(['type']);
            if (Auth::user()?->role === 'ADMIN') {
                $itemsQuery->with(['user']);
            }
            $createdItems = $itemsQuery->orderBy('order_number')->get();

            $itemRequest->refresh()->load(['user', 'type']);

            return ItemResource::collection($createdItems)->additional([
                'message' => 'Berhasil menambahkan barang.',
                'created_count' => $createdCount,
                'item_request' => new ItemRequestResource($itemRequest),
            ])->response()->setStatusCode(201);
        } catch (QueryException $e) {
            if ($e->getCode() == 23000) {
                $errorMsg = 'Kode barang sudah digunakan. Silakan gunakan kode lain.';
            } else {
                $errorMsg = 'Terjadi kesalahan pada database. Silakan coba lagi.';
            }

            return response()->json([
                'message' => $errorMsg,
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menambahkan barang. Silakan coba lagi.',
            ], 500);
        }
    }

    public function import(ImportItemsRequest $request)
    {
        try {
            $ext = strtolower($request->file('file')->getClientOriginalExtension());

            if (in_array($ext, ['xlsx', 'xls'])) {
                Excel::import(new ItemsImportExcel, $request->file('file'));
            } else {
                Excel::import(new ItemsImport, $request->file('file'));
            }

            return response()->json([
                'message' => 'Import berhasil!',
            ]);
        } catch (PhpSpreadsheetReaderException $e) {
            return response()->json([
                'message' => 'Gagal membaca file. Pastikan file Excel tidak rusak atau terproteksi.',
            ], 422);
        } catch (ValidationException $e) {
            $failures = $e->failures();
            $messages = [];
            foreach ($failures as $failure) {
                $messages[] = 'Baris ' . $failure->row() . ' pada kolom ' . implode(', ', $failure->attribute()) . ': ' . implode(', ', $failure->errors());
            }

            return response()->json([
                'message' => 'Terdapat kesalahan pada data impor: ' . implode(' ', $messages),
            ], 422);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Gagal menyimpan data ke database. Silakan periksa kembali isi file dan pastikan tidak ada duplikasi kode barang.',
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat mengimpor: ' . $e->getMessage(),
            ], 500);
        }
    }
}
