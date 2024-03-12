<?php

namespace App\Http\Controllers\Admin\Catalog\ProductCategories;

use App\Http\Controllers\Controller;
use App\Http\Requests\Catalog\ProductCategory\ProductCategoryChildrenUpdateRelationshipsRequest;
use App\Http\Resources\Identifiers\ApiEntityIdentifierResource;
use Domain\Catalog\Services\ProductCategory\ProductCategoryRelationsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductCategoryChildrenRelationshipsController extends Controller
{
    const RELATION = 'children';

    public function __construct(
        public ProductCategoryRelationsService $productCategoryRelationsService
    ) {
    }

    public function index(Request $request, int $id): JsonResponse
    {
        $params = ($request->query());
        unset($params['q']);

        data_set($data, 'relation_method', self::RELATION);
        data_set($data, 'id', $id);
        data_set($data, 'params', $params);

        $paginatedQuery = $this->productCategoryRelationsService->indexRelations($data);

        return ApiEntityIdentifierResource::collection($paginatedQuery)->response();
    }

    /**
     * @throws \ReflectionException
     */
    public function update(ProductCategoryChildrenUpdateRelationshipsRequest $request, int $id): JsonResponse
    {
        data_set($data, 'relation_data', $request->all());
        data_set($data, 'relation_method', self::RELATION);
        data_set($data, 'id', $id);

        $this->productCategoryRelationsService->updateRelations($data);

        return response()->json(null, 204);
    }
}
