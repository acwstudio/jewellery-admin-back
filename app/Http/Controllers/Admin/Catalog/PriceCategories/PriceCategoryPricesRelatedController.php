<?php

namespace App\Http\Controllers\Admin\Catalog\PriceCategories;

use App\Http\Controllers\Controller;
use App\Http\Resources\Catalog\Price\PriceCollection;
use Domain\Catalog\Services\PriceCategory\Relationships\PriceCategoryPricesRelationshipsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PriceCategoryPricesRelatedController extends Controller
{
//    const RELATION = 'prices';

    public function __construct(
        public PriceCategoryPricesRelationshipsService $service
    ) {
    }

    public function index(Request $request, int $id): JsonResponse
    {
        $params = $request->except('q');
        data_set($params, 'id', $id);

        $collection = $this->service->index($params);

        return (new PriceCollection($collection))->response();
    }
}
