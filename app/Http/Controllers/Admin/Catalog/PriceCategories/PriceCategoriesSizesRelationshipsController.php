<?php

namespace App\Http\Controllers\Admin\Catalog\PriceCategories;

use App\Http\Controllers\Controller;
use App\Http\Requests\Catalog\PriceCategory\PriceCategoriesSizesUpdateRelationshipsRequest;
use App\Http\Resources\Identifiers\ApiEntityIdentifierResource;
use Domain\Catalog\Services\PriceCategory\Relationships\PriceCategoriesSizesRelationshipsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PriceCategoriesSizesRelationshipsController extends Controller
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

        return ApiEntityIdentifierResource::collection($collection)->response();
    }

    public function update(PriceCategoriesSizesUpdateRelationshipsRequest $request, int $id): JsonResponse
    {
        $data = $request->except('q');
        data_set($data, 'id', $id);

        $this->service->update($data);

        return response()->json(null, 204);
    }
}
