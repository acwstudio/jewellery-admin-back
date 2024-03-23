<?php

namespace App\Http\Controllers\Admin\Catalog\Prices;

use App\Http\Controllers\Controller;
use App\Http\Resources\Catalog\SizeCategory\SizeCategoryResource;
use Domain\Catalog\Services\Price\Relationships\PricesSizeCategoryRelationshipsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PricesSizeCategoryRelatedController extends Controller
{
    public function __construct(protected PricesSizeCategoryRelationshipsService $service)
    {
    }

    public function index(Request $request, int $id): JsonResponse
    {
        $params = $request->except('q');
        data_set($params, 'id', $id);

        $model = $this->service->index($params);

        return (new SizeCategoryResource($model))->response();
    }
}
