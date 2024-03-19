<?php

namespace App\Http\Controllers\Admin\Catalog\Products;

use App\Http\Controllers\Controller;
use App\Http\Resources\Blog\BlogPost\BlogPostCollection;
use Domain\Catalog\Services\Product\ProductRelationsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductsBlogPostsRelatedController extends Controller
{
    const RELATION = 'blogPosts';

    public function __construct(
        public ProductRelationsService $productRelationsService
    ) {
    }

    public function index(Request $request, int $id): JsonResponse
    {
        $params = ($request->query());
        unset($params['q']);

        data_set($data, 'relation_method', self::RELATION);
        data_set($data, 'id', $id);
        data_set($data, 'params', $params);

        $collection = $this->productRelationsService->indexProductsBlogPosts($data);

        return (new BlogPostCollection($collection))->response();
    }
}
