<?php

namespace App\Http\Controllers\Admin\Catalog\Prices;

use App\Http\Controllers\Controller;
use App\Http\Requests\Catalog\Price\PricesSizeUpdateRelationshipsRequest;
use App\Http\Resources\Identifiers\ApiEntityIdentifierResource;
use Domain\Catalog\Services\Price\Relationships\PricesSizeRelationshipsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PricesSizeRelationshipsController extends Controller
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

        return (new ApiEntityIdentifierResource($model))->response();
    }

    public function update(PricesSizeUpdateRelationshipsRequest $request, int $id): JsonResponse
    {
        return \response()->json([
            'Warning' => 'use update blog_category_id field by PATCH ' .
                route('products.update',['id' => $id]) . ' instead']);
    }
}
