<?php

namespace App\Http\Controllers\Api;

use App\Repositories\TypeRepositoryInterface;
use App\Http\Resources\TypeResource;
use App\Http\Requests\StoreTypeRequest;
use App\Http\Requests\UpdateTypeRequest;
use App\Http\Controllers\Controller;

class TypeApiController extends Controller
{
    protected $typeRepository;

    public function __construct(TypeRepositoryInterface $typeRepository)
    {
        $this->typeRepository = $typeRepository;
    }

    public function index()
    {
        $types = $this->typeRepository->all();
        return TypeResource::collection($types);
    }

    public function show($id)
    {
        $type = $this->typeRepository->find($id);
        return new TypeResource($type);
    }

    public function store(StoreTypeRequest $request)
    {
        $validated = $request->validated();
        $type = $this->typeRepository->create($validated);
        return new TypeResource($type);
    }

    public function update(UpdateTypeRequest $request, $id)
    {
        $validated = $request->validated();
        $type = $this->typeRepository->update($id, $validated);
        return new TypeResource($type);
    }

    public function destroy($id)
    {
        $this->typeRepository->delete($id);
        return response()->json(['message' => 'Type deleted successfully.']);
    }
}
