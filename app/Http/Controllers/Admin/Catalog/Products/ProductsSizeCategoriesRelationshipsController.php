<?php

namespace App\Http\Controllers\Admin\Catalog\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Catalog\Product\ProductsSizeCategoriesUpdateRelationshipsRequest;
use App\Http\Resources\Identifiers\ApiEntityIdentifierResource;
use Domain\Catalog\Services\Product\ProductRelationsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductsSizeCategoriesRelationshipsController extends Controller
{
    const RELATION = 'sizeCategories';

    public function __construct(
        public ProductRelationsService $productRelationsService
    ) {
    }

    public function index(Request $request, int $id): JsonResponse
    {
        $params = ($request->query());
        unset($params['q']);

        data_set($data, 'relation_method', self::RELATION);
        data_set($data, 'id', $id);
        data_set($data, 'params', $params);

        $paginatedQuery = $this->productRelationsService->indexProductSizeCategories($data);

        return ApiEntityIdentifierResource::collection($paginatedQuery)->response();
    }

    /**
     * @throws \ReflectionException
     */
    public function update(ProductsSizeCategoriesUpdateRelationshipsRequest $request, int $id): JsonResponse
    {
        data_set($data, 'relation_data', $request->all());
        data_set($data, 'relation_method', self::RELATION);
        data_set($data, 'id', $id);

        $this->productRelationsService->updateRelations($data);

        return response()->json(null, 204);
    }
}
