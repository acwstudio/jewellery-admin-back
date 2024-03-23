<?php

namespace App\Http\Controllers\Admin\Catalog\Prices;

use App\Http\Controllers\Controller;
use App\Http\Resources\Catalog\PriceCategory\PriceCategoryResource;
use Domain\Catalog\Services\Price\Relationships\PricesPriceCategoryRelationshipsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PricesPriceCategoryRelatedController extends Controller
{
    public function __construct(
        public PricesPriceCategoryRelationshipsService $service
    ) {
    }

    public function index(Request $request, int $id): JsonResponse
    {
        $params = $request->except('q');
        data_set($params, 'id', $id);

        $model = $this->service->index($params);

        return (new PriceCategoryResource($model))->response();
    }
}
