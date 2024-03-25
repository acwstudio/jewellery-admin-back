<?php

namespace App\Http\Controllers\Admin\Catalog\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Catalog\Product\ProductsWeavesUpdateRelationshipsRequest;
use App\Http\Resources\Identifiers\ApiEntityIdentifierResource;
use Domain\Catalog\Services\Product\ProductRelationsService;
use Domain\Catalog\Services\Product\Relationships\ProductsWeavesRelationshipsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductsWeavesRelationshipsController extends Controller
{
    public function __construct(
        public ProductsWeavesRelationshipsService $service
    ) {
    }

    public function index(Request $request, int $id)
    {
        $params = $request->except('q');
        data_set($params, 'id', $id);

        $collection = $this->service->index($params);

        return ApiEntityIdentifierResource::collection($collection)->response();
    }

    public function update(ProductsWeavesUpdateRelationshipsRequest $request, int $id): JsonResponse
    {
        $data = $request->except('q');
        data_set($data, 'id', $id);

        $this->service->update($data);

        return response()->json(null, 204);
    }
}
