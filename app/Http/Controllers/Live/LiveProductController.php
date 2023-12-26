<?php

declare(strict_types=1);

namespace App\Http\Controllers\Live;

use App\Http\Controllers\Controller;
use App\Modules\Live\Enums\LiveProductSortColumnEnum;
use App\Packages\DataObjects\Live\Filter\FilterLiveProductData;
use App\Packages\DataObjects\Live\LiveProduct\GetLiveProductListData;
use App\Packages\DataObjects\Live\LiveProduct\LiveProductListData;
use App\Packages\Enums\SortOrderEnum;
use App\Packages\ModuleClients\LiveModuleClientInterface;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\QueryParameter;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Schema;

class LiveProductController extends Controller
{
    public function __construct(
        protected readonly LiveModuleClientInterface $liveModuleClient
    ) {
    }

    #[Get(
        path: '/api/v1/live/product',
        summary: 'Получить список продуктов Прямого эфира',
        tags: ['Live'],
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
                schema: new Schema(ref: '#/components/schemas/live_product_sort_column_enum', type: 'string')
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
                schema: new Schema(ref: '#/components/schemas/live_filter_live_product_data', type: 'object'),
                style: 'deepObject',
                explode: true
            ),
        ],
        responses: [
            new Response(
                response: 200,
                description: 'Коллекция продуктов Прямого эфира',
                content: new JsonContent(ref: '#/components/schemas/live_live_product_list_data')
            )
        ]
    )]
    public function getList(GetLiveProductListData $data): LiveProductListData
    {
        return $this->liveModuleClient->getLiveProducts($data);
    }

    #[Get(
        path: '/api/v1/live/product/popular',
        summary: 'Получить список популярных продуктов Прямого эфира',
        tags: ['Live'],
        parameters: [
            new QueryParameter(
                name: 'pagination',
                description: 'Пагинация',
                required: false,
                schema: new Schema(ref: '#/components/schemas/pagination_data', type: 'object'),
                style: 'deepObject',
                explode: true
            )
        ],
        responses: [
            new Response(
                response: 200,
                description: 'Коллекция продуктов Прямого эфира',
                content: new JsonContent(ref: '#/components/schemas/live_live_product_list_data')
            )
        ]
    )]
    public function getListByPopular(GetLiveProductListData $data): LiveProductListData
    {
        $newData = new GetLiveProductListData(
            sort_by: LiveProductSortColumnEnum::POPULAR,
            sort_order: SortOrderEnum::DESC,
            pagination: $data->pagination,
            filter: new FilterLiveProductData(on_live: false, last_days: config('live.product.last_days', 5))
        );
        return $this->liveModuleClient->getLiveProducts($newData);
    }

    #[Get(
        path: '/api/v1/live/product/recently',
        summary: 'Получить список недавних продуктов Прямого эфира',
        tags: ['Live'],
        parameters: [
            new QueryParameter(
                name: 'pagination',
                description: 'Пагинация',
                required: false,
                schema: new Schema(ref: '#/components/schemas/pagination_data', type: 'object'),
                style: 'deepObject',
                explode: true
            )
        ],
        responses: [
            new Response(
                response: 200,
                description: 'Коллекция продуктов Прямого эфира',
                content: new JsonContent(ref: '#/components/schemas/live_live_product_list_data')
            )
        ]
    )]
    public function getListByRecently(GetLiveProductListData $data): LiveProductListData
    {
        $newData = new GetLiveProductListData(
            sort_by: LiveProductSortColumnEnum::STARTED_AT,
            sort_order: SortOrderEnum::DESC,
            pagination: $data->pagination,
            filter: new FilterLiveProductData(on_live: false, last_days: config('live.product.last_days', 5))
        );
        return $this->liveModuleClient->getLiveProducts($newData);
    }

    #[Get(
        path: '/api/v1/live/product/on_live',
        summary: 'Получить список продуктов в Прямом эфире',
        tags: ['Live'],
        parameters: [
            new QueryParameter(
                name: 'pagination',
                description: 'Пагинация',
                required: false,
                schema: new Schema(ref: '#/components/schemas/pagination_data', type: 'object'),
                style: 'deepObject',
                explode: true
            )
        ],
        responses: [
            new Response(
                response: 200,
                description: 'Коллекция продуктов Прямого эфира',
                content: new JsonContent(ref: '#/components/schemas/live_live_product_list_data')
            )
        ]
    )]
    public function getListByOnLive(GetLiveProductListData $data): LiveProductListData
    {
        $newData = new GetLiveProductListData(
            sort_by: LiveProductSortColumnEnum::NUMBER,
            sort_order: SortOrderEnum::ASC,
            pagination: $data->pagination,
            filter: new FilterLiveProductData(on_live: true)
        );
        return $this->liveModuleClient->getLiveProducts($newData);
    }
}
