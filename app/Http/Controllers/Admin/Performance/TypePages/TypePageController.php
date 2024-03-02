<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Performance\TypePages;

use App\Http\Controllers\Controller;
use App\Http\Requests\Performance\TypePage\TypePageStoreRequest;
use App\Http\Requests\Performance\TypePage\TypePageUpdateRequest;
use App\Http\Resources\Performance\TypePage\TypePageCollection;
use App\Http\Resources\Performance\TypePage\TypePageResource;
use Domain\Performance\Services\TypePage\TypePageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TypePageController extends Controller
{
    public function __construct(public TypePageService $typePageService)
    {
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

        $items = $this->typePageService->index($data);

        return (new TypePageCollection($items))->response();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TypePageStoreRequest $request
     * @return JsonResponse
     */
    public function store(TypePageStoreRequest $request): JsonResponse
    {
        $data = $request->all();

        $typeBanner = $this->typePageService->store($data);

        return (new TypePageResource($typeBanner))
            ->response()
            ->header('Location', route('type-banners.show', [
                'id' => $typeBanner->id
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
        $model = $this->typePageService->show($id, $data);

        return (new TypePageResource($model))->response();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param TypePageUpdateRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(TypePageUpdateRequest $request, $id): JsonResponse
    {
        $data = $request->all();
        data_set($data, 'id', $id);

        $this->typePageService->update($data);

        return response()->json(null, 204);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $this->typePageService->destroy($id);

        return response()->json(null, 204);
    }
}
