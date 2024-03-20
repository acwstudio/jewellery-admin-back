<?php

namespace App\Http\Controllers\Admin\Catalog\ProductCategories;

use App\Http\Controllers\Controller;
use App\Http\Resources\Catalog\ProductCategory\ProductCategoryResource;
use Domain\Catalog\Services\ProductCategory\ProductCategoryRelationsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductCategoriesParentRelatedController extends Controller
{
    const RELATION = 'parent';

    public function __construct(
        public ProductCategoryRelationsService $productCategoryRelationsService
    ) {
    }

    public function index(Request $request, int $id): JsonResponse
    {
        data_set($data, 'relation_method', self::RELATION);
        data_set($data, 'id', $id);

        $model = $this->productCategoryRelationsService->indexProductCategoriesParent($data);

        return (new ProductCategoryResource($model))->response();
    }
}
