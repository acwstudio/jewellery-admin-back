<?php

namespace App\Http\Controllers\Admin\Catalog\SizeCategories;

use App\Http\Controllers\Controller;
use App\Http\Resources\Identifiers\ApiEntityIdentifierResource;
use Domain\Catalog\Services\SizeCategory\SizeCategoryRelationsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SizeCategorySizesRelationshipsController extends Controller
{
    const RELATION = 'sizes';

    public function __construct(
        public SizeCategoryRelationsService $sizeCategoryRelationsService
    ) {
    }

    public function index(Request $request, int $id): JsonResponse
    {
        $params = ($request->query());
        unset($params['q']);

        data_set($data, 'relation_method', self::RELATION);
        data_set($data, 'id', $id);
        data_set($data, 'params', $params);

        $paginatedQuery = $this->sizeCategoryRelationsService->indexSizeCategorySizes($data);

        return ApiEntityIdentifierResource::collection($paginatedQuery)->response();
    }

    public function update()
    {

    }
}
