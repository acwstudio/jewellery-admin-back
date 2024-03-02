<?php

namespace App\Http\Controllers\Admin\Catalog\Products;

use App\Http\Controllers\Controller;
use App\Http\Resources\Catalog\Price\PriceCollection;
use Domain\Catalog\Services\Product\ProductRelationsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductPricesRelatedController extends Controller
{
    public function __construct(
        public ProductRelationsService $productRelationsService
    ) {
    }

    public function index(Request $request, int $id): JsonResponse
    {
        $params = ($request->query());
        unset($params['q']);

        data_set($data, 'relation_method', 'prices');
        data_set($data, 'id', $id);
        data_set($data, 'params', $params);

        $collection = $this->productRelationsService->indexRelations($data);

        return (new PriceCollection($collection))->response();
    }
}
