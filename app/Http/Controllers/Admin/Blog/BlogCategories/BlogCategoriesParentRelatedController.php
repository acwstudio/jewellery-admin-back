<?php

namespace App\Http\Controllers\Admin\Blog\BlogCategories;

use App\Http\Controllers\Controller;
use App\Http\Resources\Blog\BlogCategory\BlogCategoryResource;
use Domain\Blog\Services\BlogCategory\BlogCategoryRelationsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BlogCategoriesParentRelatedController extends Controller
{
    public function __construct(
        public BlogCategoryRelationsService $blogCategoryRelationsService
    ) {
    }

    public function index(Request $request, int $id): JsonResponse
    {
        data_set($data, 'relation_method', 'parent');
        data_set($data, 'id', $id);

        $blogCategory = $this->blogCategoryRelationsService->indexRelations($data);

        return (new BlogCategoryResource($blogCategory))->response();
    }
}
