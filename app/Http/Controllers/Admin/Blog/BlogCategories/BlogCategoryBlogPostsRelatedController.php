<?php

namespace App\Http\Controllers\Admin\Blog\BlogCategories;

use App\Http\Controllers\Controller;
use App\Http\Resources\Blog\BlogPost\BlogPostCollection;
use Domain\Blog\Services\BlogCategory\BlogCategoryRelationsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BlogCategoryBlogPostsRelatedController extends Controller
{
    public function __construct(
        public BlogCategoryRelationsService $blogCategoryRelationsService
    ) {
    }

    public function index(Request $request, int $id): JsonResponse
    {
        $params = ($request->query());
        unset($params['q']);

        data_set($data, 'relation_method', 'blogPosts');
        data_set($data, 'id', $id);
        data_set($data, 'params', $params);

        $collection = $this->blogCategoryRelationsService->indexRelations($data);

        return (new BlogPostCollection($collection))->response();
    }
}
