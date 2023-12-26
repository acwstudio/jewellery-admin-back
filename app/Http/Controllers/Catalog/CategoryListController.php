<?php

declare(strict_types=1);

namespace App\Http\Controllers\Catalog;

use App\Http\Controllers\Controller;
use App\Packages\DataObjects\Catalog\Category\CategoryListItemData;
use App\Packages\DataObjects\Catalog\Category\CategoryListOptionsData;
use App\Packages\ModuleClients\CatalogModuleClientInterface;
use Illuminate\Support\Collection;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\PathParameter;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Schema;

class CategoryListController extends Controller
{
    public function __construct(
        private readonly CatalogModuleClientInterface $catalogModuleClient
    ) {
    }

    #[Get(
        path: '/api/v1/catalog/category_list',
        summary: 'Получить коллекцию списков категорий',
        requestBody: new RequestBody(
            content: new JsonContent(ref: '#/components/schemas/category_list_options_data')
        ),
        tags: ['Catalog'],
        responses: [
            new Response(
                response: 200,
                description: 'Коллекция категорий',
                content: new JsonContent(type: 'array', items: new Items(
                    ref: '#/components/schemas/category_list_item_data'
                ))
            )
        ]
    )]
    public function getCategoryList(CategoryListOptionsData $options): Collection
    {
        return $this->catalogModuleClient->getCategoryList($options);
    }

    #[Get(
        path: '/api/v1/catalog/category_list/{id}',
        summary: 'Получить список категории по ID',
        requestBody: new RequestBody(
            content: new JsonContent(ref: '#/components/schemas/category_list_options_data')
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
                description: 'Категория',
                content: new JsonContent(ref: '#/components/schemas/category_list_item_data')
            )
        ]
    )]
    public function getCategoryListItem(int $id, CategoryListOptionsData $options): CategoryListItemData
    {
        return $this->catalogModuleClient->getCategoryListItem($id, $options);
    }

    #[Get(
        path: '/api/v1/catalog/category_list/{slug}',
        summary: 'Получить список категории по слагу или аллиасам',
        requestBody: new RequestBody(
            content: new JsonContent(ref: '#/components/schemas/category_list_options_data')
        ),
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
                description: 'Категория',
                content: new JsonContent(ref: '#/components/schemas/category_list_item_data')
            )
        ]
    )]
    public function getCategoryListItemBySlug(string $slug, CategoryListOptionsData $options): CategoryListItemData
    {
        return $this->catalogModuleClient->getCategoryListItemBySlug($slug, $options);
    }
}
