<?php

namespace App\Http\Controllers\Admin\Catalog\Prices;

use App\Http\Controllers\Controller;
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
        $model = $this->priceService->show($id, $data);

        return (new PriceResource($model))->response();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Domain\Catalog\Models\Price  $price
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Price $price)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Domain\Catalog\Models\Price  $price
     * @return \Illuminate\Http\Response
     */
    public function destroy(Price $price)
    {
        //
    }
}
