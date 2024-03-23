<?php

namespace App\Http\Controllers\Admin\Catalog\Prices;

use App\Http\Controllers\Controller;
use App\Http\Requests\Catalog\Price\PricesProductUpdateRelationshipsRequest;
use App\Http\Resources\Identifiers\ApiEntityIdentifierResource;
use Domain\Catalog\Services\Price\Relationships\PricesProductRelationshipsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PricesProductRelationshipsController extends Controller
{
    public function __construct(
        public PricesProductrelationshipsService $service
    ) {
    }

    public function index(Request $request, int $id): JsonResponse
    {
        $params = $request->except('q');
        data_set($params, 'id', $id);

        $model = $this->service->index($params);

        return (new ApiEntityIdentifierResource($model))->response();
    }

    public function update(PricesProductUpdateRelationshipsRequest $request, int $id)
    {
        // HasOneThrough updating doesn't make sense. You can do something another
    }
}
