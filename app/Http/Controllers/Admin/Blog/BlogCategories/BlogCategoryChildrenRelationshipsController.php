<?php

namespace App\Http\Controllers\Admin\Blog\BlogCategories;

use App\Http\Controllers\Controller;
use App\Http\Requests\Blog\BlogCategory\BlogCategoryChildrenUpdateRelationshipsRequest;
use App\Http\Resources\Identifiers\ApiEntityIdentifierResource;
use Domain\Blog\Services\BlogCategory\BlogCategoryRelationsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BlogCategoryChildrenRelationshipsController extends Controller
{
    public function __construct(
        public BlogCategoryRelationsService $blogCategoryRelationsService
    ) {
    }

    public function index(Request $request, int $id): JsonResponse
    {
        $params = ($request->query());
        unset($params['q']);

        data_set($data, 'relation_method', 'children');
        data_set($data, 'id', $id);
        data_set($data, 'params', $params);

        $paginatedQuery = $this->blogCategoryRelationsService->indexRelations($data);

        return ApiEntityIdentifierResource::collection($paginatedQuery)->response();
    }

    /**
     * @throws \ReflectionException
     */
    public function update(BlogCategoryChildrenUpdateRelationshipsRequest $request, int $id): JsonResponse
    {
        data_set($data, 'relation_data', $request->all());
        data_set($data, 'relation_method', 'children');
        data_set($data, 'id', $id);

        $this->blogCategoryRelationsService->updateRelations($data);

        return response()->json(null, 204);
    }
}
