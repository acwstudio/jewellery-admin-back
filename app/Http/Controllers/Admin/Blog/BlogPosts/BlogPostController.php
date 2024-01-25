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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BlogPostStoreRequest $request)
    {
        //
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
     * @param  \Illuminate\Http\Request  $request
     * @param  \Domain\Blog\Models\BlogPost  $blogPost
     * @return \Illuminate\Http\Response
     */
    public function update(BlogPostUpdateRequest $request, BlogPost $blogPost)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Domain\Blog\Models\BlogPost  $blogPost
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        //
    }
}
