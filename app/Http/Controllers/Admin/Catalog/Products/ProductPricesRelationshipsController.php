<?php

namespace App\Http\Controllers\Admin\Catalog\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Catalog\Product\ProductPricesUpdateRelationshipsRequest;
use App\Http\Resources\Identifiers\ApiEntityIdentifierResource;
use Domain\Catalog\Services\Product\ProductRelationsService;
use Domain\Catalog\Services\Product\Relationships\ProductPricesRelationshipsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductPricesRelationshipsController extends Controller
{
    public function __construct(
        public ProductPricesRelationshipsService $service
    ) {
    }

    public function index(Request $request, int $id): JsonResponse
    {
        $params = $request->except('q');
        data_set($params, 'id', $id);

        $collection = $this->service->index($params);

        return ApiEntityIdentifierResource::collection($collection)->response();
    }

    /**
     * @throws \ReflectionException
     */
    public function update(ProductPricesUpdateRelationshipsRequest $request, int $id): JsonResponse
    {
        $data = $request->except('q');
        data_set($data, 'id', $id);

        $this->service->update($data);

        return response()->json(null, 204);
    }
}
