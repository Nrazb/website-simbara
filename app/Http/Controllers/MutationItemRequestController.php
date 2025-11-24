<?php

namespace App\Http\Controllers;

use App\Repositories\MutationItemRequestRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Requests\StoreMutationItemRequest;
use App\Http\Requests\UpdateMutationItemRequest;
use App\Repositories\ItemRepositoryInterface;
use App\Repositories\UserRepositoryInterface;

class MutationItemRequestController extends Controller
{
    protected $mutationItemRequestRepository;
    protected $itemRepository;
    protected $userRepository;

    public function __construct(MutationItemRequestRepositoryInterface $mutationItemRequestRepository, ItemRepositoryInterface $itemRepository, UserRepositoryInterface $userRepository)
    {
        $this->mutationItemRequestRepository = $mutationItemRequestRepository;
        $this->itemRepository = $itemRepository;
        $this->userRepository = $userRepository;
    }

    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 5);
        $mutationItemRequests = $this->mutationItemRequestRepository->all($perPage);
        return view('mutation_item_requests.index', compact('mutationItemRequests'));
    }

    public function create()
    {
        $items = $this->itemRepository->all();
        $users = $this->userRepository->all();
        return view('mutation_item_requests.create', compact('items', 'users'));
    }

    public function store(StoreMutationItemRequest $request)
    {
        $validated = $request->validated();
        try {
            $this->mutationItemRequestRepository->create($validated);
            return redirect()->route('mutation_item_requests.index')->with('success', 'Mutation item request created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to create mutation item request: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $mutationItemRequest = $this->mutationItemRequestRepository->find($id);
        return view('mutation_item_requests.show', compact('mutationItemRequest'));
    }

    public function edit($id)
    {
        $mutationItemRequest = $this->mutationItemRequestRepository->find($id);
        return view('mutation_item_requests.edit', compact('mutationItemRequest'));
    }

    public function update(UpdateMutationItemRequest $request, $id)
    {
        $validated = $request->validated();
        try {
            $this->mutationItemRequestRepository->update($id, $validated);
            return redirect()->route('mutation_item_requests.index')->with('success', 'Mutation item request updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to update mutation item request: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->mutationItemRequestRepository->delete($id);
            return redirect()->route('mutation_item_requests.index')->with('success', 'Mutation item request deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete mutation item request: ' . $e->getMessage());
        }
    }
}
