<?php

declare(strict_types=1);

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Packages\DataObjects\Blog\Post\CreatePostData;
use App\Packages\DataObjects\Blog\Post\GetPostListData;
use App\Packages\DataObjects\Blog\Post\PostData;
use App\Packages\DataObjects\Blog\Post\PostListData;
use App\Packages\DataObjects\Blog\Post\UpdatePostData;
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

class PostController extends Controller
{
    public function __construct(
        protected BlogModuleClientInterface $blogModuleClient
    ) {
    }

    #[Get(
        path: '/api/v1/blog/post/{slug}',
        description: 'Return a blog post',
        summary: 'Получить пост блога по слагу',
        tags: ['Blog'],
        parameters: [
            new PathParameter(
                name: 'slug',
                description: 'Blog post slug',
                required: true,
                schema: new Schema(type: 'string')
            )
        ],
        responses: [
            new Response(
                response: 200,
                description: 'Success response',
                content: new JsonContent(ref: '#/components/schemas/blog_post_data')
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
    public function get(string $slug): PostData
    {
        return $this->blogModuleClient->getPost($slug);
    }

    #[Get(
        path: '/api/v1/blog/post',
        summary: 'Получить посты блога',
        tags: ['Blog'],
        parameters: [
            new QueryParameter(
                name: 'category',
                description: 'Category slug',
                schema: new Schema(type: 'string'),
                example: 'category=new-category'
            ),
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
                content: new JsonContent(ref: '#/components/schemas/blog_post_list_data')
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
    public function getList(GetPostListData $data): PostListData
    {
        return $this->blogModuleClient->getPosts($data);
    }

    #[Post(
        path: '/api/v1/blog/post',
        summary: 'Создать пост блога',
        requestBody: new RequestBody(
            content: new JsonContent(ref: '#/components/schemas/blog_create_post_data')
        ),
        tags: ['Blog'],
        responses: [
            new Response(
                response: 200,
                description: 'Success response',
                content: new JsonContent(ref: '#/components/schemas/blog_post_data')
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
    public function create(CreatePostData $data): PostData
    {
        return $this->blogModuleClient->createPost($data);
    }

    #[Put(
        path: '/api/v1/blog/post',
        summary: 'Обновить пост блога',
        requestBody: new RequestBody(
            content: new JsonContent(ref: '#/components/schemas/blog_update_post_data')
        ),
        tags: ['Blog'],
        responses: [
            new Response(
                response: 200,
                description: 'Success response',
                content: new JsonContent(ref: '#/components/schemas/blog_post_data')
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
    public function update(UpdatePostData $data): PostData
    {
        return $this->blogModuleClient->updatePost($data);
    }

    #[Delete(
        path: '/api/v1/blog/post/{id}',
        summary: 'Удалить пост блога по ID',
        tags: ['Blog'],
        parameters: [
            new PathParameter(
                name: 'id',
                description: 'Blog post id',
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
            $this->blogModuleClient->deletePost($id)
        );
    }
}
