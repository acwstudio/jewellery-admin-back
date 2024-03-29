<?php

namespace App\Http\Controllers\Admin\Catalog\SizeCategories;

use App\Http\Controllers\Controller;
use App\Http\Resources\Catalog\Product\ProductCollection;
use Domain\Catalog\Services\SizeCategory\SizeCategoryRelationsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SizeCategoriesProductsRelatedController extends Controller
{
    const RELATION = 'products';

    public function __construct(
        public SizeCategoryRelationsService $sizeCategoryRelationsService
    ) {
    }

    public function index(Request $request, int $id): JsonResponse
    {
        $params = ($request->query());
        unset($params['q']);

        data_set($data, 'relation_method', self::RELATION);
        data_set($data, 'id', $id);
        data_set($data, 'params', $params);

        $collection = $this->sizeCategoryRelationsService->indexSizeCategoriesProducts($data);

        return (new ProductCollection($collection))->response();
    }
}
