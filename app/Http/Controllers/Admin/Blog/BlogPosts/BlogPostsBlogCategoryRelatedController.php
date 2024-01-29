<?php

namespace App\Http\Controllers\Admin\Blog\BlogPosts;

use App\Http\Controllers\Controller;
use App\Http\Resources\Blog\BlogCategory\BlogCategoryResource;
use Domain\Blog\Services\BlogPost\BlogPostRelationsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BlogPostsBlogCategoryRelatedController extends Controller
{
    public function __construct(
        public BlogPostRelationsService $blogPostRelationsService
    ) {
    }

    public function index(Request $request, int $id): JsonResponse
    {
        data_set($data, 'relation_method', 'blogCategory');
        data_set($data, 'id', $id);

        $blogCategory = $this->blogPostRelationsService->indexRelations($data);

        return (new BlogCategoryResource($blogCategory))->response();
    }
}
