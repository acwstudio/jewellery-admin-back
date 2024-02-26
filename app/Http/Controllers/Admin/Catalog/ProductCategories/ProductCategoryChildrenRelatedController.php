<?php

namespace App\Http\Controllers\Admin\Catalog\ProductCategories;

use App\Http\Controllers\Controller;
use App\Http\Resources\Catalog\ProductCategory\ProductCategoryCollection;
use Domain\Catalog\Services\ProductCategory\ProductCategoryRelationsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductCategoryChildrenRelatedController extends Controller
{
    public function __construct(
        public ProductCategoryRelationsService $productCategoryRelationsService
    ) {
    }

    public function index(Request $request, int $id): JsonResponse
    {
        $params = ($request->query());
        unset($params['q']);

        data_set($data, 'relation_method', 'children');
        data_set($data, 'id', $id);
        data_set($data, 'params', $params);

        $collection = $this->productCategoryRelationsService->indexRelations($data);

        return (new ProductCategoryCollection($collection))->response();
    }
}
