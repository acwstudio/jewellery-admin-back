<?php

declare(strict_types=1);

namespace App\Http\Controllers\Catalog;

use App\Http\Controllers\Controller;
use App\Packages\DataObjects\Catalog\Filter\FilterProductData;
use App\Packages\DataObjects\Catalog\Product\CreateProductData;
use App\Packages\DataObjects\Catalog\Product\ProductData;
use App\Packages\DataObjects\Catalog\Product\ProductGetListData;
use App\Packages\DataObjects\Catalog\Product\ProductItemExtendedData;
use App\Packages\DataObjects\Catalog\Product\ProductItemListData;
use App\Packages\DataObjects\Catalog\Product\ProductListAndFilterData;
use App\Packages\DataObjects\Catalog\Product\UpdateProductData;
use App\Packages\ModuleClients\CatalogModuleClientInterface;
use OpenApi\Attributes\Delete;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\PathParameter;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\Put;
use OpenApi\Attributes\QueryParameter;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Schema;

class ProductController extends Controller
{
    public function __construct(
        protected readonly CatalogModuleClientInterface $catalogModuleClient
    ) {
    }

    #[Get(
        path: '/api/v1/catalog/product',
        summary: 'Получить список продуктов',
        tags: ['Catalog'],
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
                description: 'Коллекция продуктовых элементов',
                content: new JsonContent(ref: '#/components/schemas/catalog_product_item_list_data')
            )
        ]
    )]
    public function getList(ProductGetListData $data): ProductItemListData
    {
        $data = new ProductGetListData(
            sort_by: $data->sort_by,
            sort_order: $data->sort_order,
            pagination: $data->pagination,
            filter: new FilterProductData(
                price: $data->filter?->price,
                prices: $data->filter?->prices,
                size: $data->filter?->size,
                qty_in_stock: $data->filter?->qty_in_stock,
                in_stock: $data->filter?->in_stock ?? true,
                category: $data->filter?->category,
                brands: $data->filter?->brands,
                ids: $data->filter?->ids,
                feature: $data->filter?->feature,
                sku: $data->filter?->sku,
                has_image: $data->filter?->has_image ?? true,
                is_active: $data->filter?->is_active ?? true,
                search: $data->filter?->search,
                exclude_sku: $data->filter?->exclude_sku,
                offer_price_type: $data->filter?->offer_price_type
            )
        );

        return $this->catalogModuleClient->getScoutProducts($data);
    }

    #[Get(
        path: '/api/v1/catalog/product/{slug}',
        summary: 'Получить продукт по слагу',
        tags: ['Catalog'],
        parameters: [
            new PathParameter(
                name: 'slug',
                schema: new Schema(type: 'string')
            )
        ],
        responses: [
            new Response(
                response: 200,
                description: 'Продукт',
                content: new JsonContent(ref: '#/components/schemas/catalog_product_data')
            )
        ]
    )]
    public function get(string $slug): ProductData
    {
        return $this->catalogModuleClient->getProductBySlug($slug);
    }

    #[Get(
        path: '/api/v1/catalog/product_item_extended/{id}',
        summary: 'Получить продуктовый элемент с расширенной информацией по ID',
        tags: ['Catalog'],
        parameters: [
            new PathParameter(
                name: 'id',
                schema: new Schema(type: 'integer')
            )
        ],
        responses: [
            new Response(
                response: 200,
                description: 'Продуктовый элемент',
                content: new JsonContent(ref: '#/components/schemas/catalog_product_item_extended_data')
            )
        ]
    )]
    public function getItemExtended(int $id): ProductItemExtendedData
    {
        return $this->catalogModuleClient->getProductItemExtended($id);
    }

    #[Post(
        path: '/api/v1/catalog/product',
        summary: 'Создать продукт',
        requestBody: new RequestBody(
            content: new JsonContent(ref: '#/components/schemas/catalog_create_product_data')
        ),
        tags: ['Catalog'],
        responses: [
            new Response(
                response: 200,
                description: 'Созданный продукт',
                content: new JsonContent(ref: '#/components/schemas/catalog_product_data')
            )
        ]
    )]
    public function create(CreateProductData $data): ProductData
    {
        return $this->catalogModuleClient->createProduct($data);
    }

    #[Put(
        path: '/api/v1/catalog/product/{id}',
        summary: 'Обновить продукт по ID',
        requestBody: new RequestBody(
            content: new JsonContent(ref: '#/components/schemas/catalog_update_product_data')
        ),
        tags: ['Catalog'],
        parameters: [
            new PathParameter(
                name: 'id',
                schema: new Schema(type: 'integer')
            )
        ],
        responses: [
            new Response(
                response: 200,
                description: 'Обновленный продукт',
                content: new JsonContent(ref: '#/components/schemas/catalog_product_data')
            )
        ]
    )]
    public function update(UpdateProductData $data): ProductData
    {
        return $this->catalogModuleClient->updateProduct($data);
    }

    #[Delete(
        path: '/api/v1/catalog/product/{id}',
        summary: 'Удалить продукт по ID',
        tags: ['Catalog'],
        parameters: [
            new PathParameter(
                name: 'id',
                schema: new Schema(type: 'integer')
            )
        ],
        responses: [
            new Response(response: 200, description: 'AOK')
        ]
    )]
    public function delete(int $id): \Illuminate\Http\Response
    {
        $this->catalogModuleClient->deleteProduct($id);
        return \response('');
    }

    #[Post(
        path: '/api/v1/catalog/product-by-seo',
        summary: 'Получить список продуктов по SEO',
        requestBody: new RequestBody(
            content: new JsonContent(ref: '#/components/schemas/product_get_list_data')
        ),
        tags: ['Catalog'],
        responses: [
            new Response(
                response: 200,
                description: 'Коллекция продуктов по SEO и фильтр',
                content: new JsonContent(ref: '#/components/schemas/catalog_product_list_and_filter_data')
            )
        ]
    )]
    public function getListBySeo(ProductGetListData $data): ProductListAndFilterData
    {
        return $this->catalogModuleClient->getSeoProducts($data);
    }
}
