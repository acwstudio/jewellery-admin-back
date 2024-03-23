<?php

namespace App\Http\Controllers\Admin\Catalog\PriceCategories;

use App\Http\Controllers\Controller;
use App\Http\Requests\Catalog\PriceCategory\PriceCategoryPricesUpdateRelationshipsRequest;
use App\Http\Resources\Identifiers\ApiEntityIdentifierResource;
use Domain\Catalog\Services\PriceCategory\Relationships\PriceCategoryPricesRelationshipsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PriceCategoryPricesRelationshipsController extends Controller
{
    public function __construct(
        public PriceCategoryPricesRelationshipsService $service
    ) {
    }

    public function index(Request $request, int $id): JsonResponse
    {
        $params = $request->except('q');
        data_set($params, 'id', $id);

        $collection = $this->service->index($params);

        return ApiEntityIdentifierResource::collection($collection)->response();
    }

    public function update(PriceCategoryPricesUpdateRelationshipsRequest $request, int $id): JsonResponse
    {
        $data = $request->except('q');
        data_set($data, 'id', $id);

        $this->service->update($data);

        return response()->json(null, 204);
    }
}
