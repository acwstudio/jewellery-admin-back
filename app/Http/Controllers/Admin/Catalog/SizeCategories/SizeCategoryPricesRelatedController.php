<?php

namespace App\Http\Controllers\Admin\Catalog\SizeCategories;

use App\Http\Controllers\Controller;
use App\Http\Resources\Catalog\Price\PriceCollection;
use Domain\Catalog\Services\SizeCategory\SizeCategoryRelationsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SizeCategoryPricesRelatedController extends Controller
{
    const RELATION = 'prices';

    public function __construct(
        public SizeCategoryRelationsService $sizeCategoryRelationsService
    ) {
    }

    public function index(Request $request, int $id): JsonResponse
    {
        $params = ($request->query());
        unset($params['q']);

        data_set($data, 'relation_method', self::RELATION);
        data_set($data, 'id', $id);
        data_set($data, 'params', $params);

        $collection = $this->sizeCategoryRelationsService->indexSizeCategoryPrices($data);

        return (new PriceCollection($collection))->response();
    }
}
