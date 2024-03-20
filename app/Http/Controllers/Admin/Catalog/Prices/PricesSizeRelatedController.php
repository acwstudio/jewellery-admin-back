<?php

namespace App\Http\Controllers\Admin\Catalog\Prices;

use App\Http\Controllers\Controller;
use App\Http\Resources\Catalog\Size\SizeResource;
use Domain\Catalog\Services\Price\PriceRelationsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PricesSizeRelatedController extends Controller
{
    const RELATION = 'size';

    public function __construct(
        public PriceRelationsService $priceRelationsService
    ) {
    }

    public function index(Request $request, int $id): JsonResponse
    {
        data_set($data, 'relation_method', self::RELATION);
        data_set($data, 'id', $id);

        $model = $this->priceRelationsService->indexPricesSize($data);

        return (new SizeResource($model))->response();
    }
}
