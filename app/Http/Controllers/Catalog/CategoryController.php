<?php

declare(strict_types=1);

namespace App\Http\Controllers\Catalog;

use App\Http\Controllers\Controller;
use App\Packages\DataObjects\Catalog\Category\CategoryData;
use App\Packages\DataObjects\Catalog\Category\CategoryOptionsData;
use App\Packages\DataObjects\Catalog\Category\CreateCategoryData;
use App\Packages\DataObjects\Catalog\Category\Filter\CategoryFilterData;
use App\Packages\DataObjects\Catalog\Category\Slug\CategorySlugAliasData;
use App\Packages\DataObjects\Catalog\Category\Slug\CreateCategorySlugAliasData;
use App\Packages\DataObjects\Catalog\Category\UpdateCategoryData;
use App\Packages\ModuleClients\CatalogModuleClientInterface;
use Illuminate\Support\Collection;
use OpenApi\Attributes\Delete;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\PathParameter;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\Put;
use OpenApi\Attributes\QueryParameter;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Schema;

class CategoryController extends Controller
{
    public function __construct(
        protected readonly CatalogModuleClientInterface $catalogModuleClient
    ) {
    }

    #[Get(
        path: '/api/v1/catalog/category',
        summary: 'Получить список категорий',
        requestBody: new RequestBody(
            content: new JsonContent(ref: '#/components/schemas/category_options_data')
        ),
        tags: ['Catalog'],
        parameters: [
            new QueryParameter(
                name: 'id',
                schema: new Schema(type: 'array', items: new Items(type: 'integer'))
            ),
            new QueryParameter(
                name: 'external_id',
                schema: new Schema(type: 'string')
            )
        ],
        responses: [
            new Response(
                response: 200,
                description: 'Коллекция категорий',
                content: new JsonContent(type: 'array', items: new Items(
                    ref: '#/components/schemas/category_data'
                ))
            )
        ]
    )]
    public function getCategories(CategoryFilterData $data, CategoryOptionsData $options): Collection
    {
        return $this->catalogModuleClient->getCategories($data, $options);
    }

    #[Get(
        path: '/api/v1/catalog/category/{id}',
        summary: 'Получить категорию по ID',
        requestBody: new RequestBody(
            content: new JsonContent(ref: '#/components/schemas/category_options_data')
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
                description: 'Категория по ID',
                content: new JsonContent(ref: '#/components/schemas/category_data')
            )
        ]
    )]
    public function getCategory(int $id, CategoryOptionsData $optionsData): CategoryData
    {
        return $this->catalogModuleClient->getCategory($id, $optionsData);
    }

    #[Post(
        path: '/api/v1/catalog/category',
        summary: 'Создать категорию',
        requestBody: new RequestBody(
            content: new JsonContent(ref: '#/components/schemas/create_category_data')
        ),
        tags: ['Catalog'],
        responses: [
            new Response(
                response: 200,
                description: 'Успешный ответ',
                content: new JsonContent(ref: '#/components/schemas/category_data')
            )
        ]
    )]
    public function createCategory(CreateCategoryData $data): CategoryData
    {
        return $this->catalogModuleClient->createCategory($data);
    }

    #[Put(
        path: '/api/v1/catalog/category/{id}',
        summary: 'Обновить категорию по ID',
        requestBody: new RequestBody(
            content: new JsonContent(ref: '#/components/schemas/update_category_data')
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
                description: 'Успешный ответ',
                content: new JsonContent(ref: '#/components/schemas/category_data')
            )
        ]
    )]
    public function updateCategory(UpdateCategoryData $data): CategoryData
    {
        return $this->catalogModuleClient->updateCategory($data);
    }

    #[Delete(
        path: '/api/v1/catalog/category/{id}',
        summary: 'Удалить категорию по ID',
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
    public function deleteCategory(int $id): \Illuminate\Http\Response
    {
        $this->catalogModuleClient->deleteCategory($id);
        return \response('');
    }

    #[Post(
        path: '/api/v1/catalog/category/{id}/slug',
        summary: 'Создать categorySlugAlias для категории',
        requestBody: new RequestBody(
            content: new JsonContent(ref: '#/components/schemas/create_alias_data')
        ),
        tags: ['Catalog'],
        parameters: [
            new PathParameter(
                name: 'category',
                schema: new Schema(type: 'integer')
            )
        ],
        responses: [
            new Response(
                response: 201,
                description: 'Slug alias для категории',
                content: new JsonContent(ref: '#/components/schemas/category_alias_data')
            )
        ]
    )]
    public function createCategorySlugAlias(
        int $id,
        CreateCategorySlugAliasData $createAliasData
    ): CategorySlugAliasData {
        return $this->catalogModuleClient->createCategorySlugAlias($id, $createAliasData);
    }

    #[Get(
        path: '/api/v1/catalog/category/{slug}',
        summary: 'Получение категории по слагу',
        requestBody: new RequestBody(
            content: new JsonContent(ref: '#/components/schemas/category_options_data')
        ),
        tags: ['Catalog'],
        parameters: [
            new PathParameter(
                name: 'slug',
                schema: new Schema(type: 'string')
            )
        ],
        responses: [
            new Response(response: 200, description: 'OK')
        ]
    )]
    public function getCategoryBySlug(string $slug, CategoryOptionsData $options): CategoryData
    {
        return $this->catalogModuleClient->getCategoryBySlug($slug, $options);
    }
}
