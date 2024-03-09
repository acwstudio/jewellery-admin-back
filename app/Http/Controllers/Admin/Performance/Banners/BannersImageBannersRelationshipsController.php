<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Performance\Banners;

use App\Http\Controllers\Controller;
use App\Http\Requests\Performance\Banners\BannersImageBannersUpdateRelationshipsRequest;
use App\Http\Resources\Identifiers\ApiEntityIdentifierResource;
use Domain\Performance\Services\Banner\BannerRelationsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BannersImageBannersRelationshipsController extends Controller
{
    public function __construct(
        public BannerRelationsService $bannerRelationsService
    ) {
    }

    public function index(Request $request, int $id): JsonResponse
    {
        $params = ($request->query());
        unset($params['q']);

        data_set($data, 'relation_method', 'imageBanners');
        data_set($data, 'id', $id);
        data_set($data, 'params', $params);

        $collection = $this->bannerRelationsService->indexRelations($data);

        return ApiEntityIdentifierResource::collection($collection)->response();
    }

    /**
     * @throws \ReflectionException
     */
    public function update(BannersImageBannersUpdateRelationshipsRequest $request, int $id): JsonResponse
    {
        data_set($data, 'relation_data', $request->all());
        data_set($data, 'relation_method', 'imageBanners');
        data_set($data, 'id', $id);

        $this->bannerRelationsService->updateRelations($data);

        return response()->json(null, 204);
    }
}
