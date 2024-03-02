<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Performance\TypeDevices;

use App\Http\Controllers\Controller;
use App\Http\Requests\Performance\TypeDevice\TypeDeviceImageBannersUpdateRelationshipsRequest;
use App\Http\Resources\Identifiers\ApiEntityIdentifierResource;
use Domain\Performance\Services\TypeDevice\TypeDeviceRelationsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TypeDeviceImageBannersRelationshipsController extends Controller
{
    public function __construct(
        public TypeDeviceRelationsService $typeDeviceRelationsService
    ) {
    }

    public function index(Request $request, int $id): JsonResponse
    {
        $params = ($request->query());
        unset($params['q']);

        data_set($data, 'relation_method', 'imageBanners');
        data_set($data, 'id', $id);
        data_set($data, 'params', $params);

        $collection = $this->typeDeviceRelationsService->indexRelations($data);

        return ApiEntityIdentifierResource::collection($collection)->response();
    }

    /**
     * @throws \ReflectionException
     */
    public function update(TypeDeviceImageBannersUpdateRelationshipsRequest $request, int $id): JsonResponse
    {
        data_set($data, 'relation_data', $request->all());
        data_set($data, 'relation_method', 'imageBanners');
        data_set($data, 'id', $id);

        $this->typeDeviceRelationsService->updateRelations($data);

        return response()->json(null, 204);
    }
}
