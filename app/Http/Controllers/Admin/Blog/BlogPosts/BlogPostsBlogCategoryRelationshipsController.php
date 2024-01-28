<?php

namespace App\Http\Controllers\Admin\Blog\BlogPosts;

use App\Http\Controllers\Controller;
use App\Http\Requests\Blog\BlogPost\BlogPostsBlogCategoryUpdateRelationshipsRequest;
use App\Http\Resources\Identifiers\ApiEntityIdentifierResource;
use Domain\Blog\Services\BlogPost\BlogPostRelationsService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BlogPostsBlogCategoryRelationshipsController extends Controller
{
    public function __construct(
        public BlogPostRelationsService $blogPostRelationsService
    ) {
    }

    public function index(Request $request, int $id): JsonResponse
    {
        data_set($data, 'relation_method', 'blogCategory');
        data_set($data, 'id', $id);

        $paginatedQuery = $this->blogPostRelationsService->indexRelations($data);

        return (new ApiEntityIdentifierResource($paginatedQuery))->response();
    }

    public function update(BlogPostsBlogCategoryUpdateRelationshipsRequest $request, int $id): JsonResponse
    {
        return \response()->json([
            'Warning' => 'use update blog_category_id field by PATCH ' .
                route('blog-posts.update',['id' => $id]) . ' instead']);
    }
}
