<?php

declare(strict_types=1);

namespace App\Http\Controllers\Promotions;

use App\Http\Controllers\Controller;
use App\Packages\DataObjects\Common\Pagination\PaginationData;
use App\Packages\DataObjects\Promotions\Sales\Sale\SaleData;
use App\Packages\DataObjects\Promotions\Sales\Sale\SaleListData;
use App\Packages\ModuleClients\PromotionsModuleClientInterface;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\PathParameter;
use OpenApi\Attributes\QueryParameter;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Schema;

class SaleController extends Controller
{
    public function __construct(
        protected readonly PromotionsModuleClientInterface $promotionsModuleClient
    ) {
    }

    #[Get(
        path: '/api/v1/promotions/sale',
        summary: 'Получить список акций',
        tags: ['Promotions'],
        parameters: [
            new QueryParameter(
                name: 'pagination',
                description: 'Пагинация',
                required: false,
                schema: new Schema(ref: '#/components/schemas/pagination_data', type: 'object'),
                style: 'deepObject',
                explode: true
            ),
        ],
        responses: [
            new Response(
                response: 200,
                description: 'Список акций',
                content: new JsonContent(ref: '#/components/schemas/promotions_sales_sale_list_data')
            )
        ]
    )]
    public function getList(?PaginationData $data = null): SaleListData
    {
        return $this->promotionsModuleClient->getSales($data);
    }

    #[Get(
        path: '/api/v1/promotions/sale/{slug}',
        summary: 'Получить акцию по slug',
        tags: ['Promotions'],
        parameters: [
            new PathParameter(
                name: 'slug',
                schema: new Schema(type: 'string')
            )
        ],
        responses: [
            new Response(
                response: 200,
                description: 'Акция',
                content: new JsonContent(ref: '#/components/schemas/promotions_sales_sale_data')
            )
        ]
    )]
    public function get(string $slug): SaleData
    {
        return $this->promotionsModuleClient->getSale($slug);
    }
}
