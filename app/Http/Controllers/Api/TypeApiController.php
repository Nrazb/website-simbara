<?php

namespace App\Http\Controllers\Api;

use App\Repositories\TypeRepositoryInterface;
use App\Http\Requests\StoreTypeRequest;
use App\Http\Requests\UpdateTypeRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\TypeResource;

class TypeApiController extends Controller
{
    protected $typeRepository;

    public function __construct(TypeRepositoryInterface $typeRepository)
    {
        $this->typeRepository = $typeRepository;
    }

    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 5);
        $types = $this->typeRepository->all($perPage);
        return TypeResource::collection($types);
    }

    public function store(StoreTypeRequest $request)
    {
        $validated = $request->validated();
        try {
            $type = $this->typeRepository->create($validated);
            return (new TypeResource($type))
                ->additional(['message' => 'Jenis baru ditambahkan.'])
                ->response()
                ->setStatusCode(201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menambahkan jenis baru: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function update(UpdateTypeRequest $request, $id)
    {
        $validated = $request->validated();
        try {
            $type = $this->typeRepository->update($id, $validated);
            return (new TypeResource($type))
                ->additional(['message' => 'Jenis berhasil diperbarui.'])
                ->response();
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal memperbarui jenis: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $this->typeRepository->delete($id);
            return response()->json([
                'message' => 'Berhasil menghapus jenis barang.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mengahpus jenis barang: ' . $e->getMessage(),
            ], 500);
        }
    }
}
