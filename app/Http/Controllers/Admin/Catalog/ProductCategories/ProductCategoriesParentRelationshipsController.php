<?php

namespace App\Http\Controllers\Admin\Catalog\ProductCategories;

use App\Http\Controllers\Controller;
use App\Http\Requests\Catalog\ProductCategory\ProductCategoriesParentUpdateRelationshipsRequest;
use App\Http\Resources\Identifiers\ApiEntityIdentifierResource;
use Domain\Catalog\Services\ProductCategory\ProductCategoryRelationsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductCategoriesParentRelationshipsController extends Controller
{
    const RELATION = 'parent';

    public function __construct(
        public ProductCategoryRelationsService $productCategoryRelationsService
    ) {
    }

    public function index(Request $request, int $id): JsonResponse
    {
        $data = $request->all();

        data_set($data, 'relation_method', self::RELATION);
        data_set($data, 'id', $id);

        $model = $this->productCategoryRelationsService->indexRelations($data);

        return (new ApiEntityIdentifierResource($model))->response();
    }

    public function update(ProductCategoriesParentUpdateRelationshipsRequest $request, int $id): JsonResponse
    {
        return \response()->json([
            'Warning' => 'use update parent_id field by PATCH ' .
                route('product-categories.update',['id' => $id]) . ' instead']);
    }
}
