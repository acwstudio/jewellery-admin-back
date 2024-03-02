<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Performance\ImageBanners;

use App\Http\Controllers\Controller;
use App\Http\Resources\Performance\Banner\BannerCollection;
use App\Http\Resources\Performance\ImageBanner\ImageBannerCollection;
use Domain\Performance\Services\ImageBanner\ImageBannerRelationsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ImageBannersBannersRelatedController extends Controller
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

        return (new BannerCollection($collection))->response();
    }
}
