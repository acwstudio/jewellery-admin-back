<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Performance\ImageBanners;

use App\Http\Controllers\Controller;
use App\Http\Requests\Performance\ImageBanner\ImageBannerStoreRequest;
use App\Http\Requests\Performance\ImageBanner\ImageBannerUpdateRequest;
use App\Http\Requests\Performance\ImageStorage\ImageStorageStoreRequest;
use App\Http\Resources\Performance\ImageBanner\ImageBannerCollection;
use App\Http\Resources\Performance\ImageBanner\ImageBannerResource;
use Domain\Performance\Models\ImageBanner;
use Domain\Performance\Models\TypeBanner;
use Domain\Performance\Services\ImageBanner\ImageBannerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ImageBannerController extends Controller
{
    public function __construct(public ImageBannerService $imageBannerService)
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

        $items = $this->imageBannerService->index($data);

        return (new ImageBannerCollection($items))->response();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ImageBannerStoreRequest $request
     * @return JsonResponse
     * @throws \Throwable
     */
    public function store(ImageBannerStoreRequest $request): JsonResponse
    {
        $data = $request->all();

        $imageBanner = $this->imageBannerService->store($data);

        return (new ImageBannerResource($imageBanner))
            ->response()
            ->header('Location', route('image-banners.show', [
                'id' => $imageBanner->id
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
        $model = $this->imageBannerService->show($id, $data);

        return (new ImageBannerResource($model))->response();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ImageBannerUpdateRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(ImageBannerUpdateRequest $request, int $id): JsonResponse
    {
        $data = $request->all();
        data_set($data, 'id', $id);

        $this->imageBannerService->update($data);

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
        $this->imageBannerService->destroy($id);

        return response()->json(null, 204);
    }
}
