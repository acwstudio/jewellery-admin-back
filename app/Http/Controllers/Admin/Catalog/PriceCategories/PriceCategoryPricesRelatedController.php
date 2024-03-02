<?php

namespace App\Http\Controllers\Admin\Catalog\PriceCategories;

use App\Http\Controllers\Controller;
use App\Http\Resources\Catalog\Price\PriceCollection;
use Domain\Catalog\Services\PriceCategory\PriceCategoryRelationsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PriceCategoryPricesRelatedController extends Controller
{
    public function __construct(
        public PriceCategoryRelationsService $priceCategoryRelationsService
    ) {
    }

    public function index(Request $request, int $id): JsonResponse
    {
        $params = ($request->query());
        unset($params['q']);

        data_set($data, 'relation_method', 'prices');
        data_set($data, 'id', $id);
        data_set($data, 'params', $params);

        $collection = $this->priceCategoryRelationsService->indexRelations($data);

        return (new PriceCollection($collection))->response();
    }
}
