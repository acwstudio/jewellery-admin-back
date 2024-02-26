<?php

namespace App\Http\Controllers\Admin\Catalog\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Catalog\Product\ProductsBlogPostsUpdateRelationshipsRequest;
use App\Http\Resources\Identifiers\ApiEntityIdentifierResource;
use Domain\Catalog\Services\Product\ProductRelationsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductsBlogPostsRelationshipsController extends Controller
{
    public function __construct(
        public ProductRelationsService $productRelationsService
    ) {
    }

    public function index(Request $request, int $id)
    {
        $params = ($request->query());
        unset($params['q']);

        data_set($data, 'relation_method', 'blogPosts');
        data_set($data, 'id', $id);
        data_set($data, 'params', $params);

        $collection = $this->productRelationsService->indexRelations($data);

        return ApiEntityIdentifierResource::collection($collection)->response();
    }

    /**
     * @throws \ReflectionException
     */
    public function update(ProductsBlogPostsUpdateRelationshipsRequest $request, int $id): JsonResponse
    {
        data_set($data, 'relation_data', $request->all());
        data_set($data, 'relation_method', 'blogPosts');
        data_set($data, 'id', $id);

        $this->productRelationsService->updateRelations($data);

        return response()->json(null, 204);
    }
}
