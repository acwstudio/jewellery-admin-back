<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Performance\TypePages;

use App\Http\Controllers\Controller;
use App\Http\Requests\Performance\TypePage\TypePageBannersUpdateRelationshipsRequest;
use App\Http\Resources\Identifiers\ApiEntityIdentifierResource;
use Domain\Performance\Services\TypePage\TypePageRelationsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TypePageBannersRelationshipsController extends Controller
{
    public function __construct(
        public TypePageRelationsService $typePageRelationsService
    ) {
    }

    public function index(Request $request, int $id): JsonResponse
    {
        $params = ($request->query());
        unset($params['q']);

        data_set($data, 'relation_method', 'banners');
        data_set($data, 'id', $id);
        data_set($data, 'params', $params);

        $collection = $this->typePageRelationsService->indexRelations($data);

        return ApiEntityIdentifierResource::collection($collection)->response();
    }

    /**
     * @throws \ReflectionException
     */
    public function update(TypePageBannersUpdateRelationshipsRequest $request, int $id): JsonResponse
    {
        data_set($data, 'relation_data', $request->all());
        data_set($data, 'relation_method', 'banners');
        data_set($data, 'id', $id);

        $this->typePageRelationsService->updateRelations($data);

        return response()->json(null, 204);
    }
}
