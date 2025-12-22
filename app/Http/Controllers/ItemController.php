<?php

namespace App\Http\Controllers;

use App\Exports\ItemsExport;
use App\Repositories\ItemRepositoryInterface;
use App\Http\Requests\StoreItemRequestForm;
use App\Http\Requests\UpdateItemRequestForm;
use App\Imports\ItemsImport;
use App\Imports\ItemsImportExcel;
use App\Models\Item;
use App\Models\ItemRequest;
use App\Models\User;
use App\Repositories\TypeRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ItemController extends Controller
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
            'year'    => $request->input('year'),
        ];
        $items = $this->itemRepository->all($search, $perPage, $filters);
        $unitsSelect = User::where('role', 'UNIT')->orderBy('name')->get();
        $maintenanceUnits = User::where('role', 'MAINTENANCE_UNIT')->orderBy('name')->get();
        return view('items.index', compact('items', 'types', 'users', 'years', 'unitsSelect', 'maintenanceUnits'));
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
        return view('items.create', compact('types', 'itemRequests'));
    }

    public function store(StoreItemRequestForm $request, ItemRequest $itemRequest)
    {
        $validated = $request->validated();
        $datas = [];
        try {
            for ($i = 1; $i <= (int) $validated['quantity']; $i++) {
                $data = [
                    'id' => $validated['code'] . '-' . $i,
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

                array_push($datas, $data);
            }

            $this->itemRepository->create($datas);

            $itemRequest->decrement('qty', (int) $validated['quantity']);

            return redirect()->route('items.index')->with('success', 'Berhasil menambahkan barang.');
        } catch (\Illuminate\Database\QueryException $e) {
            return $e;
            if ($e->getCode() == 23000) {
                $errorMsg = 'Kode barang sudah digunakan. Silakan gunakan kode lain.';
            } else {
                $errorMsg = 'Terjadi kesalahan pada database. Silakan coba lagi.';
            }
            return redirect()->back()->withInput()->with('error', $errorMsg);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan barang. Silakan coba lagi.');
        }
    }

    public function show($id)
    {
        $item = $this->itemRepository->find($id);
        return view('items.show', compact('item'));
    }

    public function edit($id)
    {
        $item = $this->itemRepository->find($id);
        return view('items.edit', compact('item'));
    }

    public function update(UpdateItemRequestForm $request, $id)
    {
        $validated = $request->validated();
        try {
            $this->itemRepository->update($id, $validated);
            return redirect()->route('items.index')->with('success', 'Berhasil memperbarui barang.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui barang: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->itemRepository->delete($id);
            return redirect()->route('items.index')->with('success', 'Berhasil menghapus barang.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus barang: ' . $e->getMessage());
        }
    }

    public function import(Request $request)
    {
        try {
            $validated = $request->validate([
                'file' => 'required|mimes:xlsx,csv,xls'
            ]);

            $ext = strtolower($request->file('file')->getClientOriginalExtension());

            if (in_array($ext, ['xlsx', 'xls'])) {
                Excel::import(new ItemsImportExcel, $request->file('file'));
            } else {
                Excel::import(new ItemsImport, $request->file('file'));
            }

            return redirect()->route('items.index')->with('success', 'Import berhasil!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput()->with('error', 'File yang diunggah tidak valid. Pastikan file berekstensi .xlsx, .xls, atau .csv.');
        } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal membaca file. Pastikan file Excel tidak rusak atau terproteksi.');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $messages = [];
            foreach ($failures as $failure) {
                $messages[] = 'Baris ' . $failure->row() . ' pada kolom ' . implode(', ', $failure->attribute()) . ': ' . implode(', ', $failure->errors());
            }
            return redirect()->back()->withInput()->with('error', 'Terdapat kesalahan pada data impor: <br>' . implode('<br>', $messages));
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data ke database. Silakan periksa kembali isi file dan pastikan tidak ada duplikasi kode barang.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat mengimpor: ' . $e->getMessage());
        }
    }
}
