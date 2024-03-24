<?php

namespace App\Http\Controllers\Admin\Catalog\Products;

use App\Http\Controllers\Controller;
use App\Http\Resources\Catalog\PriceCategory\PriceCategoryCollection;
use Domain\Catalog\Services\Product\ProductRelationsService;
use Domain\Catalog\Services\Product\Relationships\ProductsSizeCategoriesRelationshipsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductsSizeCategoriesRelatedController extends Controller
{
    public function __construct(
        public ProductsSizeCategoriesRelationshipsService $service
    ) {
    }

    public function index(Request $request, int $id): JsonResponse
    {
        $params = $request->except('q');
        data_set($params, 'id', $id);

        $collection = $this->service->index($params);

        return (new PriceCategoryCollection($collection))->response();
    }
}
