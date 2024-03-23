<?php

namespace App\Http\Controllers\Admin\Catalog\Prices;

use App\Http\Controllers\Controller;
use App\Http\Requests\Catalog\Price\PricesSizeCategoryUpdateRelationshipsRequest;
use App\Http\Resources\Identifiers\ApiEntityIdentifierResource;
use Domain\Catalog\Repositories\Price\Relationships\PricesSizeCategoryRelationshipsRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PricesSizeCategoryRelationshipsController extends Controller
{
    public function __construct(protected PricesSizeCategoryRelationshipsRepository $repository)
    {
    }

    public function index(Request $request, int $id): JsonResponse
    {
        $params = $request->except('q');
        data_set($params, 'id', $id);

        $model = $this->repository->index($params);

        return (new ApiEntityIdentifierResource($model))->response();
    }

    public function update(PricesSizeCategoryUpdateRelationshipsRequest $request, int $id)
    {
        // HasOneThrough updating doesn't make sense. You can do something another
    }
}
