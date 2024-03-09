<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Performance\Banners;

use App\Http\Controllers\Controller;
use App\Http\Requests\Performance\Banners\BannerStoreRequest;
use App\Http\Requests\Performance\Banners\BannerUpdateRequest;
use App\Http\Resources\Performance\Banner\BannerCollection;
use App\Http\Resources\Performance\Banner\BannerResource;
use Domain\Performance\Services\Banner\BannerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function __construct(public BannerService $bannerService)
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

        $items = $this->bannerService->index($data);

        return (new BannerCollection($items))->response();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param BannerStoreRequest $request
     * @return JsonResponse
     */
    public function store(BannerStoreRequest $request): JsonResponse
    {
        $data = $request->all();

        $banner = $this->bannerService->store($data);

        return (new BannerResource($banner))
            ->response()
            ->header('Location', route('banners.show', [
                'id' => $banner->id
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
        $model = $this->bannerService->show($id, $data);

        return (new BannerResource($model))->response();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param BannerUpdateRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(BannerUpdateRequest $request, int $id): JsonResponse
    {
        $data = $request->all();
        data_set($data, 'id', $id);

        $this->bannerService->update($data);

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
        $this->bannerService->destroy($id);

        return response()->json(null, 204);
    }
}
