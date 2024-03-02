<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Performance\Banners;

use App\Http\Controllers\Controller;
use App\Http\Resources\Performance\ImageBanner\ImageBannerCollection;
use Domain\Performance\Services\Banner\BannerRelationsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BannersImageBannersRelatedController extends Controller
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

        return (new ImageBannerCollection($collection))->response();
    }
}
