<?php

namespace App\Http\Controllers\Admin\Blog\BlogPosts;

use App\Http\Controllers\Controller;
use App\Http\Requests\Blog\BlogPost\BlogPostStoreRequest;
use App\Http\Requests\Blog\BlogPost\BlogPostUpdateRequest;
use App\Http\Resources\Blog\BlogPost\BlogPostCollection;
use App\Http\Resources\Blog\BlogPost\BlogPostResource;
use Domain\Blog\Models\BlogPost;
use Domain\Blog\Services\BlogPost\BlogPostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BlogPostController extends Controller
{
    public function __construct(
        public BlogPostService $blogPostService
    ) {
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $data = $request->all();

        $items = $this->blogPostService->index($data);

        return (new BlogPostCollection($items))->response();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param BlogPostStoreRequest $request
     * @return JsonResponse
     */
    public function store(BlogPostStoreRequest $request): JsonResponse
    {
        $data = $request->all();
        /** @var BlogPost $blogPost */
        $blogPost = $this->blogPostService->store($data);

        return (new BlogPostResource(BlogPost::find($blogPost->id)))
            ->response()
            ->header('Location', route('blog-posts.show', [
                'id' => $blogPost->id
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
        $model = $this->blogPostService->show($id, $data);

        return (new BlogPostResource($model))->response();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param BlogPostUpdateRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(BlogPostUpdateRequest $request, int $id): JsonResponse
    {
        $data = $request->all();
        data_set($data, 'id', $id);

        $this->blogPostService->update($data);

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
        $this->blogPostService->destroy($id);

        return response()->json(null, 204);
    }
}
