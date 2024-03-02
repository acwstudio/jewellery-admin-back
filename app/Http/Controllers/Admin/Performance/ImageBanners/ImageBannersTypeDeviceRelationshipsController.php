<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Performance\ImageBanners;

use App\Http\Controllers\Controller;
use App\Http\Requests\Performance\ImageBanner\ImageBannersTypeDeviceUpdateRelationshipsRequest;
use App\Http\Resources\Identifiers\ApiEntityIdentifierResource;
use Domain\Performance\Services\ImageBanner\ImageBannerRelationsService;
use Illuminate\Http\JsonResponse;

class ImageBannersTypeDeviceRelationshipsController extends Controller
{
    public function __construct(public ImageBannerRelationsService $imageBannerRelationsService)
    {
    }

    public function index(int $id): JsonResponse
    {
        data_set($data, 'relation_method', 'typeDevice');
        data_set($data, 'id', $id);

        $model = $this->imageBannerRelationsService->indexRelations($data);

        return (new ApiEntityIdentifierResource($model))->response();
    }

    /**
     * @throws \ReflectionException
     */
    public function update(ImageBannersTypeDeviceUpdateRelationshipsRequest $request, int $id): JsonResponse
    {
        data_set($data, 'relation_data', $request->all());
        data_set($data, 'relation_method', 'typeDevice');
        data_set($data, 'id', $id);

        $this->imageBannerRelationsService->updateRelations($data);

        return response()->json(null, 204);
    }
}
