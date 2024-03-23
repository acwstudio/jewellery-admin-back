<?php

namespace App\Http\Controllers\Admin\Catalog\Prices;

use App\Http\Controllers\Controller;
use App\Http\Resources\Catalog\Size\SizeResource;
use Domain\Catalog\Services\Price\Relationships\PricesSizeRelationshipsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PricesSizeRelatedController extends Controller
{
    public function __construct(
        public PricesSizeRelationshipsService $service
    ) {
    }

    public function index(Request $request, int $id): JsonResponse
    {
        $params = $request->except('q');
        data_set($params, 'id', $id);

        $model = $this->service->index($params);

        return (new SizeResource($model))->response();
    }
}
