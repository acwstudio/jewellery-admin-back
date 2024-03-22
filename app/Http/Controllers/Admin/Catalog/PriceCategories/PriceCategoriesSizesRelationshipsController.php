<?php

namespace App\Http\Controllers\Admin\Catalog\PriceCategories;

use App\Http\Controllers\Controller;
use App\Http\Requests\Catalog\PriceCategory\PriceCategoriesSizesUpdateRelationshipsRequest;
use App\Http\Resources\Identifiers\ApiEntityIdentifierResource;
use Domain\Catalog\Services\PriceCategory\PriceCategoryRelationsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PriceCategoriesSizesRelationshipsController extends Controller
{
    const RELATION = 'sizes';

    public function __construct(
        public PriceCategoryRelationsService $priceCategoryRelationsService
    ) {
    }

    public function index(Request $request, int $id): JsonResponse
    {
        $params = ($request->query());
        unset($params['q']);

        data_set($data, 'relation_method', self::RELATION);
        data_set($data, 'id', $id);
        data_set($data, 'params', $params);

        $paginatedQuery = $this->priceCategoryRelationsService->indexPriceCategorySizes($data);

        return ApiEntityIdentifierResource::collection($paginatedQuery)->response();
    }

    /**
     * @throws \ReflectionException
     */
    public function update(PriceCategoriesSizesUpdateRelationshipsRequest $request, int $id): JsonResponse
    {
        data_set($data, 'relation_data', $request->all());
        data_set($data, 'relation_method', self::RELATION);
        data_set($data, 'id', $id);

        $this->priceCategoryRelationsService->updateRelations($data);

        return response()->json(null, 204);
    }
}
