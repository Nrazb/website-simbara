<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRemoveItemUnitRequest;
use App\Repositories\RemoveItemRequestRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use App\Models\User;
use App\Models\Item;
use App\Models\RemoveItemRequest;
use Throwable;

class RemoveItemRequestController extends Controller
{
    protected $removeItemRequestRepository;

    public function __construct(RemoveItemRequestRepositoryInterface $removeItemRequestRepository)
    {
        $this->removeItemRequestRepository = $removeItemRequestRepository;
    }

    public function index(Request $request)
    {
        $users = User::whereIn('id', function ($query) {
            $query->select('user_id')->from('remove_item_requests');
        })
            ->orderBy('name')
            ->get();

        $removeItemRequests = $this->removeItemRequestRepository->all();

        $items = null;
        if (Auth::user()->role !== 'ADMIN') {
            $items = Item::where('user_id', Auth::id())->latest()->get();
        }

        return view('remove_item_requests.index', compact('removeItemRequests', 'users', 'items'));
    }

    public function store(StoreRemoveItemUnitRequest $request)
    {
        $validated = $request->validated();

        try {
            $data = [
                'user_id' => Auth::id(),
                'item_id' => $validated['item_id'],
                'status' => 'PROCESS',
                'unit_confirmed' => false,
            ];
            $this->removeItemRequestRepository->create($data);
            return back()->with('success', 'Pengajuan penghapusan barang ditambahkan.');
        } catch (QueryException $e) {
            return back()->with('error', 'Kesalahan database saat menambahkan pengajuan.');
        } catch (Throwable $e) {
            return back()->with('error', 'Gagal menambahkan pengajuan.');
        }
    }

    public function confirmUnit(RemoveItemRequest $removeItemRequest)
    {
        $user = Auth::user();
        try {
            if ($user->id !== $removeItemRequest->user_id) {
                return back()->with('error', 'Akses ditolak.');
            }
            if ($removeItemRequest->unit_confirmed) {
                return back()->with('success', 'Unit sudah dikonfirmasi.');
            }
            $this->removeItemRequestRepository->update($removeItemRequest->id, ['unit_confirmed' => true]);
            return back()->with('success', 'Unit dikonfirmasi.');
        } catch (QueryException $e) {
            return back()->with('error', 'Kesalahan database saat memproses.');
        } catch (Throwable $e) {
            return back()->with('error', 'Gagal memproses.');
        }
    }

    public function updateStatus(Request $request, RemoveItemRequest $removeItemRequest)
    {
        $user = Auth::user();
        try {
            if ($user->role !== 'ADMIN') {
                return back()->with('error', 'Akses ditolak.');
            }
            $status = $request->input('status');
            $allowed = ['PROCESS', 'STORED', 'AUCTIONED'];
            if (!in_array($status, $allowed, true)) {
                return back()->with('error', 'Status tidak valid.');
            }
            $this->removeItemRequestRepository->update($removeItemRequest->id, ['status' => $status]);
            return back()->with('success', 'Status penghapusan diperbarui.');
        } catch (QueryException $e) {
            return back()->with('error', 'Kesalahan database saat memproses.');
        } catch (Throwable $e) {
            return back()->with('error', 'Gagal memproses.');
        }
    }
}
