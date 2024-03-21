<?php

namespace App\Http\Controllers\Admin\Catalog\Sizes;

use App\Http\Controllers\Controller;
use App\Http\Resources\Identifiers\ApiEntityIdentifierResource;
use Domain\Catalog\Services\Size\SizeRelationsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SizePricesRelationshipsController extends Controller
{
    const RELATION = 'prices';

    public function __construct(
        public SizeRelationsService $sizeRelationsService
    ) {
    }

    public function index(Request $request, int $id): JsonResponse
    {
        $params = ($request->query());
        unset($params['q']);

        data_set($data, 'relation_method', self::RELATION);
        data_set($data, 'id', $id);
        data_set($data, 'params', $params);

        $paginatedQuery = $this->sizeRelationsService->indexSizePrices($data);

        return ApiEntityIdentifierResource::collection($paginatedQuery)->response();
    }

    public function update()
    {

    }
}
