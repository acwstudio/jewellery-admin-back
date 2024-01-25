<?php

namespace App\Http\Controllers\Admin\Blog\BlogCategories;

use App\Http\Controllers\Controller;
use App\Http\Resources\Identifiers\ApiEntityIdentifierResource;
use Domain\Blog\Services\BlogCategory\BlogCategoryRelationsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BlogCategoryBlogPostsRelationshipsController extends Controller
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

        $paginatedQuery = $this->blogCategoryRelationsService->indexRelations($data);

        return ApiEntityIdentifierResource::collection($paginatedQuery)->response();
    }

    public function update()
    {

    }
}
