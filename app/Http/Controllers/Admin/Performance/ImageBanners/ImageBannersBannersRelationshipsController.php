<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Performance\ImageBanners;

use App\Http\Controllers\Controller;
use App\Http\Requests\Performance\ImageBanner\ImageBannersBannersUpdateRelationshipsRequest;
use App\Http\Resources\Identifiers\ApiEntityIdentifierResource;
use Domain\Performance\Services\ImageBanner\ImageBannerRelationsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ImageBannersBannersRelationshipsController extends Controller
{
    public function __construct(
        public ImageBannerRelationsService $imageBannerRelationsService
    ) {
    }

    public function index(Request $request, int $id): JsonResponse
    {
        $params = ($request->query());
        unset($params['q']);

        data_set($data, 'relation_method', 'banners');
        data_set($data, 'id', $id);
        data_set($data, 'params', $params);

        $collection = $this->imageBannerRelationsService->indexRelations($data);

        return ApiEntityIdentifierResource::collection($collection)->response();
    }

    /**
     * @throws \ReflectionException
     */
    public function update(ImageBannersBannersUpdateRelationshipsRequest $request, int $id): JsonResponse
    {
        data_set($data, 'relation_data', $request->all());
        data_set($data, 'relation_method', 'banners');
        data_set($data, 'id', $id);

        $this->imageBannerRelationsService->updateRelations($data);

        return response()->json(null, 204);
    }
}
