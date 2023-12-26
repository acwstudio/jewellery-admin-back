<?php

declare(strict_types=1);

namespace App\Http\Controllers\Collections;

use App\Http\Controllers\Controller;
use App\Packages\DataObjects\Collections\Favorite\CreateFavoriteData;
use App\Packages\DataObjects\Collections\Favorite\FavoriteData;
use App\Packages\DataObjects\Collections\Favorite\FavoriteListData;
use App\Packages\DataObjects\Collections\Favorite\GetListFavoriteData;
use App\Packages\DataObjects\Collections\Favorite\UpdateFavoriteData;
use App\Packages\ModuleClients\CollectionModuleClientInterface;
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

class FavoriteController extends Controller
{
    public function __construct(
        protected readonly CollectionModuleClientInterface $collectionModuleClient
    ) {
    }

    #[Get(
        path: '/api/v1/collections/favorite',
        summary: 'Получить список избранных коллекций',
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
        ],
        responses: [
            new Response(
                response: 200,
                description: 'Список избранных коллекций',
                content: new JsonContent(ref: '#/components/schemas/collections_favorite_list_data')
            )
        ]
    )]
    public function getList(GetListFavoriteData $data): FavoriteListData
    {
        return $this->collectionModuleClient->getFavorites($data);
    }

    #[Get(
        path: '/api/v1/collections/favorite/{slug}',
        summary: 'Получить избранную коллекцию по слагу',
        tags: ['Collections'],
        parameters: [
            new PathParameter(
                name: 'slug',
                schema: new Schema(type: 'string')
            )
        ],
        responses: [
            new Response(
                response: 200,
                description: 'Избранная коллекция',
                content: new JsonContent(ref: '#/components/schemas/collections_favorite_data')
            )
        ]
    )]
    public function get(string $slug): ?FavoriteData
    {
        return $this->collectionModuleClient->getFavoriteBySlug($slug);
    }

    #[Post(
        path: '/api/v1/collections/favorite',
        summary: 'Создать избранную коллекцию',
        requestBody: new RequestBody(
            content: new JsonContent(ref: '#/components/schemas/collections_create_favorite_data')
        ),
        tags: ['Collections'],
        responses: [
            new Response(
                response: 200,
                description: 'Созданная избранная коллекция',
                content: new JsonContent(ref: '#/components/schemas/collections_favorite_data')
            )
        ]
    )]
    public function create(CreateFavoriteData $data): FavoriteData
    {
        return $this->collectionModuleClient->createFavorite($data);
    }

    #[Put(
        path: '/api/v1/collections/favorite/{id}',
        summary: 'Обновить избранную коллекцию по ID',
        requestBody: new RequestBody(
            content: new JsonContent(ref: '#/components/schemas/collections_update_favorite_data')
        ),
        tags: ['Collections'],
        parameters: [
            new PathParameter(
                name: 'id',
                schema: new Schema(type: 'integer')
            )
        ],
        responses: [
            new Response(
                response: 200,
                description: 'Обновленная избранная коллекция',
                content: new JsonContent(ref: '#/components/schemas/collections_favorite_data')
            )
        ]
    )]
    public function update(UpdateFavoriteData $data): FavoriteData
    {
        return $this->collectionModuleClient->updateFavorite($data);
    }

    #[Delete(
        path: '/api/v1/collections/favorite/{id}',
        summary: 'Удалить избранную коллекцию по ID',
        tags: ['Collections'],
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
        $this->collectionModuleClient->deleteFavorite($id);
        return \response('');
    }
}
