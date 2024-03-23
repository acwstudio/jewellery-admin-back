<?php

namespace App\Http\Controllers\Admin\Catalog\Prices;

use App\Http\Controllers\Controller;
use App\Http\Resources\Identifiers\ApiEntityIdentifierResource;
use Domain\Catalog\Repositories\Price\Relationships\PricesSizeCategoryRelationshipsRepository;
use Illuminate\Http\Request;

class PricesSizeCategoryRelationshipsController extends Controller
{
    public function __construct(protected PricesSizeCategoryRelationshipsRepository $repository)
    {
    }

    public function index(Request $request, int $id)
    {
        $params = $request->except('q');
        data_set($params, 'id', $id);

        $model = $this->repository->index($params);

        return (new ApiEntityIdentifierResource($model))->response();
    }
}
