<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Performance\TypeDevices;

use App\Http\Controllers\Controller;
use App\Http\Requests\Performance\TypeDevice\TypeDeviceStoreRequest;
use App\Http\Requests\Performance\TypeDevice\TypeDeviceUpdateRequest;
use App\Http\Resources\Performance\TypeDevice\TypeDeviceCollection;
use App\Http\Resources\Performance\TypeDevice\TypeDeviceResource;
use Domain\Performance\Models\TypeDevice;
use Domain\Performance\Services\TypeDevice\TypeDeviceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TypeDeviceController extends Controller
{
    public function __construct(public TypeDeviceService $typeDeviceService)
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

        $items = $this->typeDeviceService->index($data);

        return (new TypeDeviceCollection($items))->response();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TypeDeviceStoreRequest $request
     * @return JsonResponse
     */
    public function store(TypeDeviceStoreRequest $request): JsonResponse
    {
        $data = $request->all();

        $typeDevice = $this->typeDeviceService->store($data);

        return (new TypeDeviceResource($typeDevice))
            ->response()
            ->header('Location', route('type-devices.show', [
                'id' => $typeDevice->id
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
        $model = $this->typeDeviceService->show($id, $data);

        return (new TypeDeviceResource($model))->response();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param TypeDeviceUpdateRequest $request
     * @param int $id
     * @return JsonResponse
     * @throws \Throwable
     */
    public function update(TypeDeviceUpdateRequest $request, int $id): JsonResponse
    {
        $data = $request->all();
        data_set($data, 'id', $id);

        $this->typeDeviceService->update($data);

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
        $this->typeDeviceService->destroy($id);

        return response()->json(null, 204);
    }
}
