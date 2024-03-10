<?php

namespace App\Http\Controllers\Admin\Catalog\PriceCategories;

use App\Http\Controllers\Controller;
use Domain\Catalog\Services\PriceCategory\PriceCategoryRelationsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PriceCategoriesSizesRelatedController extends Controller
{
    const RELATION = 'sizes';

    public function __construct(
        public PriceCategoryRelationsService $priceCategoryRelationsService
    ) {
    }

    public function index(Request $request, int $id): JsonResponse
    {
        $params = ($request->query());
        unset($params['q']);

        data_set($data, 'relation_method', self::RELATION);
        data_set($data, 'id', $id);
        data_set($data, 'params', $params);

        $collection = $this->priceCategoryRelationsService->indexRelations($data);

        return (new SizeCollection($collection))->response();
    }
}
