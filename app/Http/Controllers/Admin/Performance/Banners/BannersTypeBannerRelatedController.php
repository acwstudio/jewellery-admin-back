<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Performance\Banners;

use App\Http\Controllers\Controller;
use App\Http\Resources\Performance\TypeBanner\TypeBannerResource;
use Domain\Performance\Services\Banner\BannerRelationsService;
use Illuminate\Http\JsonResponse;

class BannersTypeBannerRelatedController extends Controller
{
    public function __construct(public BannerRelationsService $bannerRelationsService)
    {
    }

    public function index(int $id): JsonResponse
    {
        data_set($data, 'relation_method', 'typeBanner');
        data_set($data, 'id', $id);

        $model = $this->bannerRelationsService->indexRelations($data);

        return (new TypeBannerResource($model))->response();
    }
}
