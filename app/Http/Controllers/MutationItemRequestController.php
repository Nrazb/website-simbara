<?php

namespace App\Http\Controllers;

use App\Repositories\MutationItemRequestRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Requests\StoreMutationItemRequest;
use App\Http\Requests\UpdateMutationItemRequest;

class MutationItemRequestController extends Controller
{
    protected $mutationItemRequestRepository;

    public function __construct(MutationItemRequestRepositoryInterface $mutationItemRequestRepository)
    {
        $this->mutationItemRequestRepository = $mutationItemRequestRepository;
    }

    public function index()
    {
        $mutationItemRequests = $this->mutationItemRequestRepository->all();
        return view('mutation_item_requests.index', compact('mutationItemRequests'));
    }

    public function create()
    {
        return view('mutation_item_requests.create');
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
