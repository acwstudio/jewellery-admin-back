<?php

declare(strict_types=1);

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Packages\DataObjects\Blog\Category\CategoryData;
use App\Packages\DataObjects\Blog\Category\CategoryListData;
use App\Packages\DataObjects\Blog\Category\CreateCategoryData;
use App\Packages\DataObjects\Blog\Category\GetCategoryListData;
use App\Packages\DataObjects\Blog\Category\UpdateCategoryData;
use App\Packages\DataObjects\Common\Response\SuccessData;
use App\Packages\Enums\SortOrderEnum;
use App\Packages\ModuleClients\BlogModuleClientInterface;
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

class CategoryController extends Controller
{
    public function __construct(
        protected BlogModuleClientInterface $blogModuleClient
    ) {
    }

    #[Get(
        path: '/api/v1/blog/category/{slug}',
        summary: 'Получить категорию блога по слагу',
        tags: ['Blog'],
        parameters: [
            new PathParameter(
                name: 'slug',
                description: 'Blog category slug',
                required: true,
                schema: new Schema(type: 'string')
            )
        ],
        responses: [
            new Response(
                response: 200,
                description: 'Success response',
                content: new JsonContent(ref: '#/components/schemas/blog_category_data')
            ),
            new Response(
                response: 500,
                description: 'Failure response',
                content: new JsonContent(ref: '#/components/schemas/error')
            ),
            new Response(
                response: 404,
                description: 'Failure response',
                content: new JsonContent(ref: '#/components/schemas/error')
            )
        ],
    )]
    public function get(string $slug): CategoryData
    {
        return $this->blogModuleClient->getCategory($slug);
    }

    #[Get(
        path: '/api/v1/blog/category',
        summary: 'Получить категории блога',
        tags: ['Blog'],
        parameters: [
            new QueryParameter(
                name: 'pagination[page]',
                schema: new Schema(type: 'integer'),
                example: 'pagination[page]=1'
            ),
            new QueryParameter(
                name: 'pagination[per_page]',
                schema: new Schema(type: 'integer'),
                example: 'pagination[per_page]=15'
            ),
            new QueryParameter(
                name: 'sort[column]',
                schema: new Schema(type: 'string'),
                example: 'sort[column]=id'
            ),
            new QueryParameter(
                name: 'sort[order_by]',
                schema: new Schema(type: 'string', enum: SortOrderEnum::class),
                example: 'sort[order_by]=asc'
            )
        ],
        responses: [
            new Response(
                response: 200,
                description: 'Success response',
                content: new JsonContent(ref: '#/components/schemas/blog_category_list_data')
            ),
            new Response(
                response: 500,
                description: 'Failure response',
                content: new JsonContent(ref: '#/components/schemas/error')
            ),
            new Response(
                response: 404,
                description: 'Failure response',
                content: new JsonContent(ref: '#/components/schemas/error')
            )
        ],
    )]
    public function getList(GetCategoryListData $data): CategoryListData
    {
        return $this->blogModuleClient->getCategories($data);
    }

    #[Post(
        path: '/api/v1/blog/category',
        summary: 'Создать категорию блога',
        requestBody: new RequestBody(
            content: new JsonContent(ref: '#/components/schemas/blog_create_category_data')
        ),
        tags: ['Blog'],
        responses: [
            new Response(
                response: 200,
                description: 'Success response',
                content: new JsonContent(ref: '#/components/schemas/blog_category_data')
            ),
            new Response(
                response: 500,
                description: 'Failure response',
                content: new JsonContent(ref: '#/components/schemas/error')
            ),
            new Response(
                response: 404,
                description: 'Failure response',
                content: new JsonContent(ref: '#/components/schemas/error')
            )
        ],
    )]
    public function create(CreateCategoryData $data): CategoryData
    {
        return $this->blogModuleClient->createCategory($data);
    }

    #[Put(
        path: '/api/v1/blog/category',
        summary: 'Обновить категорию блога',
        requestBody: new RequestBody(
            content: new JsonContent(ref: '#/components/schemas/blog_update_category_data')
        ),
        tags: ['Blog'],
        responses: [
            new Response(
                response: 200,
                description: 'Success response',
                content: new JsonContent(ref: '#/components/schemas/blog_category_data')
            ),
            new Response(
                response: 500,
                description: 'Failure response',
                content: new JsonContent(ref: '#/components/schemas/error')
            ),
            new Response(
                response: 404,
                description: 'Failure response',
                content: new JsonContent(ref: '#/components/schemas/error')
            )
        ],
    )]
    public function update(UpdateCategoryData $data): CategoryData
    {
        return $this->blogModuleClient->updateCategory($data);
    }

    #[Delete(
        path: '/api/v1/blog/category/{id}',
        summary: 'Удалить категорию блога по ID',
        tags: ['Blog'],
        parameters: [
            new PathParameter(
                name: 'id',
                description: 'Blog category id',
                required: true,
                schema: new Schema(type: 'integer')
            )
        ],
        responses: [
            new Response(
                response: 200,
                description: 'Success response',
                content: new JsonContent(ref: '#/components/schemas/success_data')
            ),
            new Response(
                response: 500,
                description: 'Failure response',
                content: new JsonContent(ref: '#/components/schemas/error')
            ),
            new Response(
                response: 404,
                description: 'Failure response',
                content: new JsonContent(ref: '#/components/schemas/error')
            )
        ],
    )]
    public function delete(int $id): SuccessData
    {
        return new SuccessData(
            $this->blogModuleClient->deleteCategory($id)
        );
    }
}
