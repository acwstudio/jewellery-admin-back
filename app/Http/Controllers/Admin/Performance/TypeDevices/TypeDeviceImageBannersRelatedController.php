<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Performance\TypeDevices;

use App\Http\Controllers\Controller;
use App\Http\Resources\Performance\TypeDevice\TypeDeviceCollection;
use Domain\Performance\Services\TypeDevice\TypeDeviceRelationsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TypeDeviceImageBannersRelatedController extends Controller
{
    public function __construct(
        public TypeDeviceRelationsService $typeDeviceRelationsService
    ) {
    }

    public function index(Request $request, int $id): JsonResponse
    {
        $params = ($request->query());
        unset($params['q']);

        data_set($data, 'relation_method', 'imageBanners');
        data_set($data, 'id', $id);
        data_set($data, 'params', $params);

        $collection = $this->typeDeviceRelationsService->indexRelations($data);

        return (new TypeDeviceCollection($collection))->response();
    }
}
