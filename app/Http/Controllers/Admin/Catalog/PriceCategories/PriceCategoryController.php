<?php

namespace App\Http\Controllers\Admin\Catalog\PriceCategories;

use App\Http\Controllers\Controller;
use App\Http\Requests\Catalog\PriceCategory\PriceCategoryStoreRequest;
use App\Http\Requests\Catalog\PriceCategory\PriceCategoryUpdateRequest;
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
     * @param PriceCategoryStoreRequest $request
     * @return JsonResponse
     * @throws \Throwable
     */
    public function store(PriceCategoryStoreRequest $request): JsonResponse
    {
        $data = $request->all();
        /** @var PriceCategory $model */
        $model = $this->priceCategoryService->store($data);

        return (new PriceCategoryResource(PriceCategory::find($model->id)))
            ->response()
            ->header('Location', route('price-categories.show', [
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
        $model = $this->priceCategoryService->show($id, $data);

        return (new PriceCategoryResource($model))->response();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param PriceCategoryUpdateRequest $request
     * @param int $id
     * @return JsonResponse
     * @throws \Throwable
     */
    public function update(PriceCategoryUpdateRequest $request, int $id): JsonResponse
    {
        $data = $request->all();
        data_set($data, 'id', $id);

        $this->priceCategoryService->update($data);

        return response()->json(null, 204);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     * @throws \Throwable
     */
    public function destroy(int $id): JsonResponse
    {
        $this->priceCategoryService->destroy($id);

        return response()->json(null, 204);
    }
}
