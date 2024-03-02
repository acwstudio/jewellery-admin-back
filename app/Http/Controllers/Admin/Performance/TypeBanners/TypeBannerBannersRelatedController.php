<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Performance\TypeBanners;

use App\Http\Controllers\Controller;
use App\Http\Resources\Performance\Banner\BannerCollection;
use Domain\Performance\Services\TypeBanner\TypeBannerRelationsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TypeBannerBannersRelatedController extends Controller
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

        return (new BannerCollection($collection))->response();
    }
}
