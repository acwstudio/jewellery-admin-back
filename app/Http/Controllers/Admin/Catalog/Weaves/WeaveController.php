<?php

namespace App\Http\Controllers\Admin\Catalog\Weaves;

use App\Http\Controllers\Controller;
use App\Http\Requests\Catalog\Weave\WeaveStoreRequest;
use App\Http\Requests\Catalog\Weave\WeaveUpdateRequest;
use App\Http\Resources\Catalog\Weave\WeaveCollection;
use App\Http\Resources\Catalog\Weave\WeaveResource;
use Domain\Catalog\Models\Weave;
use Domain\Catalog\Services\Weave\WeaveService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WeaveController extends Controller
{
    public function __construct(
        public WeaveService $weaveService
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

        $items = $this->weaveService->index($data);

        return (new WeaveCollection($items))->response();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(WeaveStoreRequest $request)
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
        $model = $this->weaveService->show($id, $data);

        return (new WeaveResource($model))->response();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Domain\Catalog\Models\Weave  $weave
     * @return \Illuminate\Http\Response
     */
    public function update(WeaveUpdateRequest $request, int $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Domain\Catalog\Models\Weave  $weave
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        //
    }
}
