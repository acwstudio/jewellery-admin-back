<?php

namespace App\Http\Controllers\Admin\Catalog\Prices;

use App\Http\Controllers\Controller;
use App\Http\Resources\Catalog\Product\ProductResource;
use Domain\Catalog\Services\Price\PriceRelationsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PricesProductRelatedController extends Controller
{
    const RELATION = 'product';

    public function __construct(
        public PriceRelationsService $priceRelationsService
    ) {
    }

    public function index(Request $request, int $id): JsonResponse
    {
        data_set($data, 'relation_method', self::RELATION);
        data_set($data, 'id', $id);

        $model = $this->priceRelationsService->indexPricesProduct($data);

        return (new ProductResource($model))->response();
    }
}
