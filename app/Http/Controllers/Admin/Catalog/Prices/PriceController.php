<?php

namespace App\Http\Controllers\Admin\Catalog\Prices;

use App\Http\Controllers\Controller;
use App\Http\Requests\Catalog\Price\PriceStoreRequest;
use App\Http\Requests\Catalog\Price\PriceUpdateRequest;
use App\Http\Resources\Catalog\Price\PriceCollection;
use App\Http\Resources\Catalog\Price\PriceResource;
use Domain\Catalog\Models\Price;
use Domain\Catalog\Services\Price\PriceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PriceController extends Controller
{
    public function __construct(
        public PriceService $priceService
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

        $items = $this->priceService->index($data);

        return (new PriceCollection($items))->response();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PriceStoreRequest $request
     * @return JsonResponse
     * @throws \Throwable
     */
    public function store(PriceStoreRequest $request): JsonResponse
    {
        $data = $request->all();

        $model = $this->priceService->store($data);

        return (new PriceResource(Price::find($model->id)))
            ->response()
            ->header('Location', route('prices.show', [
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
        $model = $this->priceService->show($id, $data);

        return (new PriceResource($model))->response();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param PriceUpdateRequest $request
     * @param int $id
     * @return JsonResponse
     * @throws \Throwable
     */
    public function update(PriceUpdateRequest $request, int $id): JsonResponse
    {
        $data = $request->all();
        data_set($data, 'id', $id);

        $this->priceService->update($data);

        return response()->json(null, 204);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     * @throws \Throwable
     */
    public function destroy(int $id)
    {
        $this->priceService->destroy($id);

        return response()->json(null, 204);
    }
}
