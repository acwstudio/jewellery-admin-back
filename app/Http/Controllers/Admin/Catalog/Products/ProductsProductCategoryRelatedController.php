<?php

namespace App\Http\Controllers\Admin\Catalog\Products;

use App\Http\Controllers\Controller;
use App\Http\Resources\Catalog\ProductCategory\ProductCategoryResource;
use Domain\Catalog\Services\Product\ProductRelationsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductsProductCategoryRelatedController extends Controller
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

        $model = $this->productRelationsService->indexProductsProductCategory($data);

        return (new ProductCategoryResource($model))->response();
    }
}
