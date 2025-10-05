<?php

namespace App\Http\Controllers;

use App\Models\Type;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTypeRequest;
use App\Http\Requests\UpdateTypeRequest;
use App\Repositories\TypeRepositoryInterface;

class TypeController extends Controller
{
    protected $typeRepository;

    public function __construct(TypeRepositoryInterface $typeRepository)
    {
        $this->typeRepository = $typeRepository;
    }

    public function index()
    {
        $types = $this->typeRepository->all();
        return view('types.index', compact('types'));
    }

    public function create()
    {
        return view('types.create');
    }

    public function store(StoreTypeRequest $request)
    {
        $validated = $request->validated();
        try {
            $this->typeRepository->create($validated);
            return redirect()->route('types.index')->with('success', 'Type created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to create type: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $type = $this->typeRepository->find($id);
        return view('types.show', compact('type'));
    }

    public function edit($id)
    {
        $type = $this->typeRepository->find($id);
        return view('types.edit', compact('type'));
    }

    public function update(UpdateTypeRequest $request, $id)
    {
        $validated = $request->validated();
        try {
            $this->typeRepository->update($id, $validated);
            return redirect()->route('types.index')->with('success', 'Type updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to update type: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->typeRepository->delete($id);
            return redirect()->route('types.index')->with('success', 'Type deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete type: ' . $e->getMessage());
        }
    }
}
