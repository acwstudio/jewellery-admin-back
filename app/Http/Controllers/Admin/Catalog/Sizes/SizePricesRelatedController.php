<?php

namespace App\Http\Controllers\Admin\Catalog\Sizes;

use App\Http\Controllers\Controller;
use App\Http\Resources\Catalog\Price\PriceCollection;
use Domain\Catalog\Services\Size\SizeRelationsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SizePricesRelatedController extends Controller
{
    const RELATION = 'prices';

    public function __construct(
        public SizeRelationsService $sizeRelationsService
    ) {
    }

    public function index(Request $request, int $id): JsonResponse
    {
        $params = ($request->query());
        unset($params['q']);

        data_set($data, 'relation_method', self::RELATION);
        data_set($data, 'id', $id);
        data_set($data, 'params', $params);

        $collection = $this->sizeRelationsService->indexSizePrices($data);

        return (new PriceCollection($collection))->response();
    }
}
