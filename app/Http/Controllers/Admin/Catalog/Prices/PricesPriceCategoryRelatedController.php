<?php

namespace App\Http\Controllers\Admin\Catalog\Prices;

use App\Http\Controllers\Controller;
use App\Http\Resources\Catalog\PriceCategory\PriceCategoryResource;
use Domain\Catalog\Services\Price\PriceRelationsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PricesPriceCategoryRelatedController extends Controller
{
    const RELATION = 'priceCategory';

    public function __construct(
        public PriceRelationsService $priceRelationsService
    ) {
    }

    public function index(Request $request, int $id): JsonResponse
    {
        data_set($data, 'relation_method', self::RELATION);
        data_set($data, 'id', $id);

        $model = $this->priceRelationsService->indexRelations($data);

        return (new PriceCategoryResource($model))->response();
    }
}
