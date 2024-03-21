<?php

namespace App\Http\Controllers\Admin\Catalog\SizeCategories;

use App\Http\Controllers\Controller;
use App\Http\Resources\Catalog\SizeCategory\SizeCategoryCollection;
use App\Http\Resources\Catalog\SizeCategory\SizeCategoryResource;
use Domain\Catalog\Models\SizeCategory;
use Domain\Catalog\Services\SizeCategory\SizeCategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SizeCategoryController extends Controller
{
    public function __construct(
        public SizeCategoryService $sizeCategoryService
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

        $items = $this->sizeCategoryService->index($data);

        return (new SizeCategoryCollection($items))->response();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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
        $model = $this->sizeCategoryService->show($id, $data);

        return (new SizeCategoryResource($model))->response();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Domain\Catalog\Models\SizeCategory  $sizeCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SizeCategory $sizeCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Domain\Catalog\Models\SizeCategory  $sizeCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(SizeCategory $sizeCategory)
    {
        //
    }
}
