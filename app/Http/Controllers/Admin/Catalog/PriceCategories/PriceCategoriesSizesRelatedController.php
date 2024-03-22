<?php

namespace App\Http\Controllers\Admin\Catalog\PriceCategories;

use App\Http\Controllers\Controller;
use App\Http\Resources\Catalog\Size\SizeCollection;
use Domain\Catalog\Services\PriceCategory\PriceCategoryRelationsService;
use Domain\Catalog\Services\PriceCategory\Relationships\PriceCategoriesSizesRelationshipsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PriceCategoriesSizesRelatedController extends Controller
{
    const RELATION = 'sizes';

    public function __construct(
        public PriceCategoriesSizesRelationshipsService $service
    ) {
    }

    public function index(Request $request, int $id): JsonResponse
    {
        $params = $request->except('q');
        data_set($params, 'id', $id);

        $collection = $this->service->index($params);

        return (new SizeCollection($collection))->response();
    }
}
