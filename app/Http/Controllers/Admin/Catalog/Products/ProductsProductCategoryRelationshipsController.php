<?php

namespace App\Http\Controllers\Admin\Catalog\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Catalog\Product\ProductsProductCategoryUpdateRelationshipsRequest;
use App\Http\Resources\Identifiers\ApiEntityIdentifierResource;
use Domain\Catalog\Services\Product\ProductRelationsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductsProductCategoryRelationshipsController extends Controller
{
    const RELATION = 'productCategory';

    public function __construct(
        public ProductRelationsService $productRelationsService
    ) {
    }

    public function index(Request $request, int $id): JsonResponse
    {
        data_set($data, 'relation_method', self::RELATION);
        data_set($data, 'id', $id);

        $model = $this->productRelationsService->indexRelations($data);

        return (new ApiEntityIdentifierResource($model))->response();
    }

    public function update(ProductsProductCategoryUpdateRelationshipsRequest $request, int $id): JsonResponse
    {
        return \response()->json([
            'Warning' => 'use update blog_category_id field by PATCH ' .
                route('products.update',['id' => $id]) . ' instead']);
    }
}
