<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Performance\Banners;

use App\Http\Controllers\Controller;
use App\Http\Resources\Performance\TypePage\TypePageResource;
use Domain\Performance\Services\Banner\BannerRelationsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BannersTypePageRelatedController extends Controller
{
    public function __construct(public BannerRelationsService $bannerRelationsService)
    {
    }

    public function index(int $id): JsonResponse
    {
        data_set($data, 'relation_method', 'typePage');
        data_set($data, 'id', $id);

        $model = $this->bannerRelationsService->indexRelations($data);

        return (new TypePageResource($model))->response();
    }
}
