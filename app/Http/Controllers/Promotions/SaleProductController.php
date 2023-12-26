<?php

declare(strict_types=1);

namespace App\Http\Controllers\Promotions;

use App\Http\Controllers\Controller;
use App\Packages\DataObjects\Promotions\Sales\CatalogProduct\CatalogProductListData;
use App\Packages\DataObjects\Promotions\Sales\CatalogProduct\GetCatalogProductListData;
use App\Packages\ModuleClients\PromotionsModuleClientInterface;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\QueryParameter;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Schema;

class SaleProductController extends Controller
{
    public function __construct(
        protected readonly PromotionsModuleClientInterface $promotionsModuleClient
    ) {
    }

    #[Get(
        path: '/api/v1/promotions/sale_product',
        summary: 'Получить список акционных товаров',
        tags: ['Promotions'],
        parameters: [
            new QueryParameter(
                name: 'sale_id',
                description: 'Идентификатор Акции (можно передавать множественное значение через запятую)',
                required: false,
                schema: new Schema(type: 'string'),
            ),
            new QueryParameter(
                name: 'sale_slug',
                description: 'Слаг Акции (можно передавать множественное значение через запятую)',
                required: false,
                schema: new Schema(type: 'string'),
            ),
            new QueryParameter(
                name: 'is_active',
                description: 'Активность акционных товаров',
                required: false,
                schema: new Schema(type: 'boolean'),
            ),
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
                description: 'Список акционных товаров',
                content: new JsonContent(ref: '#/components/schemas/promotions_sales_catalog_product_list_data')
            )
        ]
    )]
    public function getList(GetCatalogProductListData $data): CatalogProductListData
    {
        return $this->promotionsModuleClient->getCatalogProducts($data);
    }
}
