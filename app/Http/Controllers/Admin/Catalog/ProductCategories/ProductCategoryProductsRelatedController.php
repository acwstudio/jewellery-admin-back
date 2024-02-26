<?php

namespace App\Http\Controllers\Admin\Catalog\ProductCategories;

use App\Http\Controllers\Controller;
use App\Http\Resources\Catalog\Product\ProductCollection;
use Domain\Catalog\Services\ProductCategory\ProductCategoryRelationsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductCategoryProductsRelatedController extends Controller
{
    public function __construct(
        public ProductCategoryRelationsService $productCategoryRelationsService
    ) {
    }

    public function index(Request $request, int $id): JsonResponse
    {
        $params = ($request->query());
        unset($params['q']);

        data_set($data, 'relation_method', 'products');
        data_set($data, 'id', $id);
        data_set($data, 'params', $params);

        $collection = $this->productCategoryRelationsService->indexRelations($data);

        return (new ProductCollection($collection))->response();
    }
}
