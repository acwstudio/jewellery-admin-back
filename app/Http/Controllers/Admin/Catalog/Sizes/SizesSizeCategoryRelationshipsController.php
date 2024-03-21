<?php

namespace App\Http\Controllers\Admin\Catalog\Sizes;

use App\Http\Controllers\Controller;
use App\Http\Resources\Identifiers\ApiEntityIdentifierResource;
use Domain\Catalog\Services\Size\SizeRelationsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SizesSizeCategoryRelationshipsController extends Controller
{
    const RELATION = 'sizeCategory';

    public function __construct(
        public SizeRelationsService $sizeRelationsService
    ) {
    }

    public function index(Request $request, int $id): JsonResponse
    {
        data_set($data, 'relation_method', self::RELATION);
        data_set($data, 'id', $id);

        $model = $this->sizeRelationsService->indexSizesSizeCategory($data);

        return (new ApiEntityIdentifierResource($model))->response();
    }

    public function update()
    {

    }
}
