<?php

namespace App\Http\Controllers\Admin\Blog\BlogCategories;

use App\Http\Controllers\Controller;
use App\Http\Requests\Blog\BlogCategory\BlogCategoryStoreRequest;
use App\Http\Requests\Blog\BlogCategory\BlogCategoryUpdateRequest;
use App\Http\Resources\Blog\BlogCategory\BlogCategoryCollection;
use App\Http\Resources\Blog\BlogCategory\BlogCategoryResource;
use Domain\Blog\Models\BlogCategory;
use Domain\Blog\Services\BlogCategory\BlogCategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BlogCategoryController extends Controller
{
    public function __construct(
        public BlogCategoryService $blogCategoryService
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $data = $request->all();

        $items = $this->blogCategoryService->index($data);

        return (new BlogCategoryCollection($items))->response();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param BlogCategoryStoreRequest $request
     * @return JsonResponse
     * @throws \Throwable
     */
    public function store(BlogCategoryStoreRequest $request): JsonResponse
    {
        $data = $request->all();

        $model = $this->blogCategoryService->store($data);

        return (new BlogCategoryResource($model))
            ->response()
            ->header('Location', route('blog-categories.show', [
                'id' => $model->id
            ]));
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $data = $request->all();
        data_set($data, 'id', $id);
        $model = $this->blogCategoryService->show($id, $data);

        return (new BlogCategoryResource($model))->response();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param BlogCategoryUpdateRequest $request
     * @param int $id
     * @return JsonResponse
     * @throws \Throwable
     */
    public function update(BlogCategoryUpdateRequest $request, int $id): JsonResponse
    {
        $data = $request->all();
        data_set($data, 'id', $id);

        $this->blogCategoryService->update($data);

        return response()->json(null, 204);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $this->blogCategoryService->destroy($id);

        return response()->json(null, 204);
    }
}
