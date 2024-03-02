<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Performance\ImageBanners;

use App\Http\Controllers\Controller;
use App\Http\Requests\Performance\TypeBanner\TypeBannerStoreRequest;
use App\Http\Requests\Performance\TypeBanner\TypeBannerUpdateRequest;
use App\Http\Resources\Performance\TypeBanner\TypeBannerCollection;
use App\Http\Resources\Performance\TypeBanner\TypeBannerResource;
use Domain\Performance\Models\TypeBanner;
use Domain\Performance\Services\TypeBanner\TypeBannerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TypeBannerController extends Controller
{
    public function __construct(public TypeBannerService $typeBannerService)
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

        $items = $this->typeBannerService->index($data);

        return (new TypeBannerCollection($items))->response();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TypeBannerStoreRequest $request
     * @return JsonResponse
     * @throws \Throwable
     */
    public function store(TypeBannerStoreRequest $request)
    {
        $data = $request->all();

        $typeBanner = $this->typeBannerService->store($data);

        return (new TypeBannerResource($typeBanner))
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
        $model = $this->typeBannerService->show($id, $data);

        return (new TypeBannerResource($model))->response();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param TypeBannerUpdateRequest $request
     * @param int $id
     * @return JsonResponse
     * @throws \Throwable
     */
    public function update(TypeBannerUpdateRequest $request, int $id): JsonResponse
    {
        $data = $request->all();
        data_set($data, 'id', $id);

        $this->typeBannerService->update($data);

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
        $this->typeBannerService->destroy($id);

        return response()->json(null, 204);
    }
}
