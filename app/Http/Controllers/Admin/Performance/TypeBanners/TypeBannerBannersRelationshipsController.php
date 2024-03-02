<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Performance\TypeBanners;

use App\Http\Controllers\Controller;
use App\Http\Requests\Performance\TypeBanner\TypeBannerBannersUpdateRelationshipsRequest;
use App\Http\Resources\Identifiers\ApiEntityIdentifierResource;
use Domain\Performance\Services\TypeBanner\TypeBannerRelationsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TypeBannerBannersRelationshipsController extends Controller
{
    public function __construct(
        public TypeBannerRelationsService $typeBannerRelationsService
    ) {
    }

    public function index(Request $request, int $id): JsonResponse
    {
        $params = ($request->query());
        unset($params['q']);

        data_set($data, 'relation_method', 'banners');
        data_set($data, 'id', $id);
        data_set($data, 'params', $params);

        $collection = $this->typeBannerRelationsService->indexRelations($data);

        return ApiEntityIdentifierResource::collection($collection)->response();
    }

    /**
     * @throws \ReflectionException
     */
    public function update(TypeBannerBannersUpdateRelationshipsRequest $request, int $id): JsonResponse
    {
        data_set($data, 'relation_data', $request->all());
        data_set($data, 'relation_method', 'banners');
        data_set($data, 'id', $id);

        $this->typeBannerRelationsService->updateRelations($data);

        return response()->json(null, 204);
    }
}
