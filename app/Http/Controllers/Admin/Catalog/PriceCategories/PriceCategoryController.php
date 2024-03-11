<?php

namespace App\Http\Controllers\Admin\Catalog\PriceCategories;

use App\Http\Controllers\Controller;
use App\Http\Resources\Catalog\PriceCategory\PriceCategoryCollection;
use App\Http\Resources\Catalog\PriceCategory\PriceCategoryResource;
use Domain\Catalog\Models\PriceCategory;
use Domain\Catalog\Services\PriceCategory\PriceCategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PriceCategoryController extends Controller
{
    public function __construct(
        public PriceCategoryService $priceCategoryService
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

        $items = $this->priceCategoryService->index($data);

        return (new PriceCategoryCollection($items))->response();
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
        $model = $this->priceCategoryService->show($id, $data);

        return (new PriceCategoryResource($model))->response();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Domain\Catalog\Models\PriceCategory  $priceCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PriceCategory $priceCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Domain\Catalog\Models\PriceCategory  $priceCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(PriceCategory $priceCategory)
    {
        //
    }
}
