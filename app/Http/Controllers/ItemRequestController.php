<?php

namespace App\Http\Controllers;

use App\Repositories\ItemRequestRepositoryInterface;
use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use App\Models\ItemRequest;
use App\Models\User;
use App\Repositories\TypeRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemRequestController extends Controller
{
    protected $itemRequestRepository;
    protected $typeRepository;

    public function __construct(ItemRequestRepositoryInterface $itemRequestRepository, TypeRepositoryInterface $typeRepository)
    {
        $this->itemRequestRepository = $itemRequestRepository;
        $this->typeRepository = $typeRepository;
    }

    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 5);

        $types = $this->typeRepository->all();

        $users = User::whereIn('id', function ($query) {
            $query->select('user_id')->from('item_requests');
        })
            ->orderBy('name')
            ->get();

        $years = ItemRequest::selectRaw('YEAR(created_at) as year')
            ->when(Auth::user()->role !== 'ADMIN', function ($query) {
                $query->where('user_id', Auth::user()->id);
            })
            ->distinct()
            ->orderBy('year', 'desc')
            ->get();

        $baseQuery = $this->itemRequestRepository->all();

        if (Auth::user()->role !== 'ADMIN') {
            $baseQuery->where('user_id', Auth::user()->id);
            $baseQuery->select([
                'id',
                'user_id',
                'type_id',
                'name',
                'detail',
                'qty',
                'reason',
                'sent_at',
                'created_at',
                'updated_at',
                'deleted_at',
            ]);
        }

        if ($request->filled('status')) {
            if ($request->status === 'draft') {
                $baseQuery->whereNull('sent_at');
            } elseif ($request->status === 'sent') {
                $baseQuery->whereNotNull('sent_at');
            } elseif ($request->status === 'deleted') {
                $baseQuery->onlyTrashed();
            }
        }

        $itemRequests = $baseQuery
            ->latest()
            ->paginate($perPage)
            ->appends($request->all());

        return view('item_requests.index', compact('itemRequests', 'types', 'users', 'years'));
    }



    public function store(StoreItemRequest $request)
    {
        $validated = $request->validated();

        // Status default: draft → sent_at = null
        $validated['sent_at'] = null;
        $validated['user_id'] = Auth::id();

        try {
            $this->itemRequestRepository->create($validated);
            return back()->with('success', 'Usulan berhasil dibuat sebagai DRAFT.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with(
                'error',
                'Gagal membuat usulan: ' . $e->getMessage()
            );
        }
    }

    public function update(UpdateItemRequest $request, $id)
    {
        $item = ItemRequest::findOrFail($id);

        // Cegah edit jika sudah dikirim
        if ($item->sent_at !== null) {
            return back()->with('error', 'Usulan sudah dikirim dan tidak dapat diedit.');
        }

        try {
            $this->itemRequestRepository->update($id, $request->validated());
            return back()->with('success', 'Usulan berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui usulan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $item = ItemRequest::findOrFail($id);

        // Cegah hapus jika sudah dikirim
        if ($item->sent_at !== null) {
            return back()->with('error', 'Usulan yang sudah dikirim tidak dapat dihapus.');
        }

        try {
            $this->itemRequestRepository->delete($id);
            return back()->with('success', 'Usulan berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus usulan: ' . $e->getMessage());
        }
    }

    public function send($id)
    {
        $item = ItemRequest::findOrFail($id);

        if ($item->sent_at !== null) {
            return back()->with('error', 'Usulan sudah dikirim sebelumnya.');
        }

        $item->update([
            'sent_at' => now()
        ]);

        return back()->with('success', 'Usulan berhasil dikirim.');
    }

    public function show($id)
    {
        $item = ItemRequest::with(['user', 'type'])->findOrFail($id);

        return view('item_requests.show', compact('item'));
    }
}
