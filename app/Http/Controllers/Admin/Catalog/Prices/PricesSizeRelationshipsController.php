<?php

namespace App\Http\Controllers\Admin\Catalog\Prices;

use App\Http\Controllers\Controller;
use App\Http\Requests\Catalog\Price\PricesSizeUpdateRelationshipsRequest;
use App\Http\Resources\Identifiers\ApiEntityIdentifierResource;
use Domain\Catalog\Services\Price\PriceRelationsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PricesSizeRelationshipsController extends Controller
{
    const RELATION = 'size';

    public function __construct(
        public PriceRelationsService $priceRelationsService
    ) {
    }

    public function index(Request $request, int $id): JsonResponse
    {
        data_set($data, 'relation_method', self::RELATION);
        data_set($data, 'id', $id);

        $model = $this->priceRelationsService->indexPricesSize($data);

        return (new ApiEntityIdentifierResource($model))->response();
    }

    public function update(PricesSizeUpdateRelationshipsRequest $request, int $id): JsonResponse
    {
        return \response()->json([
            'Warning' => 'use update blog_category_id field by PATCH ' .
                route('products.update',['id' => $id]) . ' instead']);
    }
}
