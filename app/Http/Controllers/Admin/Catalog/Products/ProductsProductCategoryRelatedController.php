<?php

namespace App\Http\Controllers\Admin\Catalog\Products;

use App\Http\Controllers\Controller;
use App\Http\Resources\Catalog\ProductCategory\ProductCategoryResource;
use Domain\Catalog\Services\Product\Relationships\ProductsProductCategoryRelationshipsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductsProductCategoryRelatedController extends Controller
{
    public function __construct(
        public ProductsProductCategoryRelationshipsService $service
    ) {
    }

    public function index(Request $request, int $id): JsonResponse
    {
        $params = $request->except('q');
        data_set($params, 'id', $id);

        $model = $this->service->index($params);

        return (new ProductCategoryResource($model))->response();
    }
}
