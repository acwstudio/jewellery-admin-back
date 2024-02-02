<?php

namespace App\Http\Controllers\Admin\Blog\BlogPosts;

use App\Http\Controllers\Controller;
use App\Http\Resources\Catalog\Product\ProductCollection;
use Domain\Blog\Services\BlogPost\BlogPostRelationsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BlogPostsProductsRelatedController extends Controller
{
    public function __construct(
        public BlogPostRelationsService $blogPostRelationsService
    ) {
    }

    public function index(Request $request, int $id): JsonResponse
    {
        $params = ($request->query());
        unset($params['q']);

        data_set($data, 'relation_method', 'products');
        data_set($data, 'id', $id);
        data_set($data, 'params', $params);

        $products = $this->blogPostRelationsService->indexRelations($data);

        return (new ProductCollection($products))->response();
    }
}
