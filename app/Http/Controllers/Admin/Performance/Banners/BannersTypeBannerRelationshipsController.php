<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Performance\Banners;

use App\Http\Controllers\Controller;
use App\Http\Requests\Performance\Banner\BannersImageBannersUpdateRelationshipsRequest;
use App\Http\Requests\Performance\Banner\BannersTypeBannerUpdateRelationshipsRequest;
use App\Http\Resources\Identifiers\ApiEntityIdentifierResource;
use Domain\Performance\Services\Banner\BannerRelationsService;
use Illuminate\Http\JsonResponse;

class BannersTypeBannerRelationshipsController extends Controller
{
    public function __construct(public BannerRelationsService $bannerRelationsService)
    {
    }

    public function index(int $id): JsonResponse
    {
        data_set($data, 'relation_method', 'typeBanner');
        data_set($data, 'id', $id);

        $model = $this->bannerRelationsService->indexRelations($data);

        return (new ApiEntityIdentifierResource($model))->response();
    }

    /**
     * @throws \ReflectionException
     */
    public function update(BannersTypeBannerUpdateRelationshipsRequest $request, int $id): JsonResponse
    {
        data_set($data, 'relation_data', $request->all());
        data_set($data, 'relation_method', 'typeBanner');
        data_set($data, 'id', $id);

        $this->bannerRelationsService->updateRelations($data);

        return response()->json(null, 204);
    }
}
