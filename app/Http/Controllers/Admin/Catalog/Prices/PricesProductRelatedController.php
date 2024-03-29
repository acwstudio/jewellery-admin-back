<?php

namespace App\Http\Controllers\Admin\Catalog\Prices;

use App\Http\Controllers\Controller;
use App\Http\Resources\Catalog\Product\ProductResource;
use Domain\Catalog\Services\Price\Relationships\PricesProductRelationshipsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PricesProductRelatedController extends Controller
{
    public function __construct(
        public PricesProductRelationshipsService $service
    ) {
    }

    public function index(Request $request, int $id): JsonResponse
    {
        $params = $request->except('q');
        data_set($params, 'id', $id);

        $model = $this->service->index($params);

        return (new ProductResource($model))->response();
    }
}
