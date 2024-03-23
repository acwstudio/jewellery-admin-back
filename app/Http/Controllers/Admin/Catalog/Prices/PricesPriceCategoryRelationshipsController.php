<?php

namespace App\Http\Controllers\Admin\Catalog\Prices;

use App\Http\Controllers\Controller;
use App\Http\Requests\Catalog\Price\PricesPriceCategoryUpdateRelationshipsRequest;
use App\Http\Resources\Identifiers\ApiEntityIdentifierResource;
use Domain\Catalog\Services\Price\Relationships\PricesPriceCategoryRelationshipsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PricesPriceCategoryRelationshipsController extends Controller
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

        return (new ApiEntityIdentifierResource($model))->response();
    }

    public function update(PricesPriceCategoryUpdateRelationshipsRequest $request, int $id): JsonResponse
    {
        $data = $request->except('q');
        data_set($data, 'id', $id);

        $this->service->update($data);

        return response()->json(null, 204);
    }
}
