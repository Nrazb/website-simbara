<?php

namespace App\Http\Controllers;

use App\Exports\ItemsExport;
use App\Repositories\ItemRepositoryInterface;
use App\Http\Requests\StoreItemRequestForm;
use App\Http\Requests\UpdateItemRequestForm;
use App\Imports\ItemsImport;
use App\Models\Item;
use App\Models\User;
use App\Repositories\TypeRepositoryInterface;
use Illuminate\Http\Request;
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

        $users = User::whereIn('id', function($query) {
            $query->select('user_id')->from('items');
            })
            ->orderBy('name')
            ->get();
        $years = Item::selectRaw('acquisition_year as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->get();
        $filters = [
            'user_id' => $request->input('user_id'),
            'year'    => $request->input('year'),
        ];
        $items = $this->itemRepository->all($search, $perPage, $filters);
        return view('items.index', compact('items', 'types', 'users', 'years'));
    }

    public function create()
    {
        $maintenanceUnits = User::where('role', 'MAINTENANCE_UNIT')->get();
        $types = $this->typeRepository->all();
        return view('items.create', compact('types', 'maintenanceUnits'));
    }

    public function store(StoreItemRequestForm $request)
    {
        $validated = $request->validated();
        try {
            $this->itemRepository->create($validated);
            return redirect()->route('items.index')->with('success', 'Berhasil menambahkan barang.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan barang: ' . $e->getMessage());
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
        $request->validate([
            'file' => 'required|mimes:xlsx,csv,xls'
        ]);

        Excel::import(new ItemsImport, $request->file('file'));

        return redirect()->route('items.index')->with('success', 'Import berhasil!');
    }
}
