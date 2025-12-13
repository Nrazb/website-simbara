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

        if (Auth::user()->role !== 'admin') {
            $baseQuery->where('user_id', Auth::user()->id);
        }

        $itemRequests = $baseQuery
            ->latest()
            ->paginate($perPage)
            ->appends([
                'per_page' => $perPage,
                'search' => $request->input('search'),
                'user_id' => $request->input('user_id'),
                'year' => $request->input('year'),
            ]);

        return view('item_requests.index', compact('itemRequests', 'types', 'users', 'years'));
    }



    public function store(StoreItemRequest $request)
    {
        $validated = $request->validated();
        try {
            $this->itemRequestRepository->create($validated);
            return back()->with('success', 'Item request created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to create item request: ' . $e->getMessage());
        }
    }

    public function update(UpdateItemRequest $request, $id)
    {
        $validated = $request->validated();
        try {
            $this->itemRequestRepository->update($id, $validated);
            return back()->with('success', 'Item request updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to update item request: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->itemRequestRepository->delete($id);
            return back()->with('success', 'Item request deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete item request: ' . $e->getMessage());
        }
    }
}
