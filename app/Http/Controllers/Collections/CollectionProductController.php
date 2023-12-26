<?php

declare(strict_types=1);

namespace App\Http\Controllers\Collections;

use App\Http\Controllers\Controller;
use App\Packages\DataObjects\Catalog\Product\ProductListData;
use App\Packages\DataObjects\Collections\CollectionProduct\GetListCollectionProductData;
use App\Packages\ModuleClients\CollectionModuleClientInterface;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\QueryParameter;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Schema;

class CollectionProductController extends Controller
{
    public function __construct(
        protected readonly CollectionModuleClientInterface $collectionModuleClient
    ) {
    }

    #[Get(
        path: '/api/v1/collections/collection/{id}/product',
        summary: 'Получить список продуктов коллекции',
        tags: ['Collections'],
        parameters: [
            new QueryParameter(
                name: 'pagination',
                description: 'Пагинация',
                required: false,
                schema: new Schema(ref: '#/components/schemas/pagination_data', type: 'object'),
                style: 'deepObject',
                explode: true
            ),
            new QueryParameter(
                name: 'sort_by',
                description: 'Поле сортировки',
                required: false,
                schema: new Schema(ref: '#/components/schemas/catalog_product_sort_column', type: 'string')
            ),
            new QueryParameter(
                name: 'sort_order',
                description: 'Порядок сортировки',
                required: false,
                schema: new Schema(ref: '#/components/schemas/sort_order', type: 'string')
            ),
            new QueryParameter(
                name: 'filter',
                description: 'Фильтрация',
                required: false,
                schema: new Schema(ref: '#/components/schemas/catalog_filter_product_data', type: 'object'),
                style: 'deepObject',
                explode: true
            ),
        ],
        responses: [
            new Response(
                response: 200,
                description: 'Коллекция продуктов',
                content: new JsonContent(ref: '#/components/schemas/catalog_product_list_data')
            )
        ]
    )]
    public function getList(GetListCollectionProductData $data): ?ProductListData
    {
        return $this->collectionModuleClient->getCollectionProducts($data);
    }
}
