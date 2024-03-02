<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Performance\ImageBanners;

use App\Http\Controllers\Controller;
use App\Http\Resources\Performance\TypeDevice\TypeDeviceResource;
use Domain\Performance\Services\ImageBanner\ImageBannerRelationsService;
use Illuminate\Http\JsonResponse;

class ImageBannersTypeDeviceRelatedController extends Controller
{
    public function __construct(public ImageBannerRelationsService $imageBannerRelationsService)
    {
    }

    public function index(int $id): JsonResponse
    {
        data_set($data, 'relation_method', 'typeDevice');
        data_set($data, 'id', $id);

        $model = $this->imageBannerRelationsService->indexRelations($data);

        return (new TypeDeviceResource($model))->response();
    }
}
