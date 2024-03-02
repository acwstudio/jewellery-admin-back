<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Performance\TypePages;

use App\Http\Controllers\Controller;
use App\Http\Resources\Performance\Banner\BannerCollection;
use Domain\Performance\Services\TypePage\TypePageRelationsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TypePageBannersRelatedController extends Controller
{
    public function __construct(
        public TypePageRelationsService $typePageRelationsService
    ) {
    }

    public function index(Request $request, int $id): JsonResponse
    {
        $params = ($request->query());
        unset($params['q']);

        data_set($data, 'relation_method', 'banners');
        data_set($data, 'id', $id);
        data_set($data, 'params', $params);

        $collection = $this->typePageRelationsService->indexRelations($data);

        return (new BannerCollection($collection))->response();
    }
}
