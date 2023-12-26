<?php

declare(strict_types=1);

namespace App\Http\Controllers\Catalog;

use App\Http\Controllers\Controller;
use App\Modules\Catalog\Services\CatalogProductFilterService;
use App\Packages\DataObjects\Catalog\Product\Filter\CatalogProductFilterListData;
use App\Packages\DataObjects\Catalog\Product\Filter\GetListProductFilterData;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\QueryParameter;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Schema;

class CatalogFilterController extends Controller
{
    public function __construct(
        protected readonly CatalogProductFilterService $catalogProductFilterService
    ) {
    }

    #[Get(
        path: '/api/v1/catalog/filter',
        summary: 'Получить коллекцию фильтров',
        tags: ['Catalog'],
        parameters: [
            new QueryParameter(
                name: 'applied_filter',
                description: 'Примененные фильтры',
                required: false,
                schema: new Schema(ref: '#/components/schemas/catalog_filter_product_data', type: 'object'),
                style: 'deepObject',
                explode: true
            ),
        ],
        responses: [
            new Response(
                response: 200,
                description: 'Коллекция фильтров продукта',
                content: new JsonContent(ref: '#/components/schemas/catalog_product_filter_list_data')
            )
        ]
    )]
    public function getList(GetListProductFilterData $data): CatalogProductFilterListData
    {
        return $this->catalogProductFilterService->getList($data);
    }
}
