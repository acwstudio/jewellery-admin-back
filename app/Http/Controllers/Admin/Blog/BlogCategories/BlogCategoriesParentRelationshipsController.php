<?php

namespace App\Http\Controllers\Admin\Blog\BlogCategories;

use App\Http\Controllers\Controller;
use App\Http\Requests\Blog\BlogCategory\BlogCategoriesParentUpdateRelationshipsRequest;
use App\Http\Resources\Identifiers\ApiEntityIdentifierResource;
use Domain\Blog\Services\BlogCategory\BlogCategoryRelationsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BlogCategoriesParentRelationshipsController extends Controller
{
    public function __construct(
        public BlogCategoryRelationsService $blogCategoryRelationsService
    ) {
    }

    public function index(Request $request, int $id): JsonResponse
    {
        $data = $request->all();

        data_set($data, 'relation_method', 'parent');
        data_set($data, 'id', $id);

        $blogPosts = $this->blogCategoryRelationsService->indexRelations($data);

        return (new ApiEntityIdentifierResource($blogPosts))->response();
    }

    public function update(BlogCategoriesParentUpdateRelationshipsRequest $request, int $id): JsonResponse
    {
        return \response()->json([
            'Warning' => 'use update parent_id field by PATCH ' .
                route('blog-categories.update',['id' => $id]) . ' instead']);
    }
}
