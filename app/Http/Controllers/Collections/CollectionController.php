<?php

declare(strict_types=1);

namespace App\Http\Controllers\Collections;

use App\Http\Controllers\Controller;
use App\Packages\DataObjects\Collections\Collection\CollectionData;
use App\Packages\DataObjects\Collections\Collection\CollectionListData;
use App\Packages\DataObjects\Collections\Collection\CreateCollectionData;
use App\Packages\DataObjects\Collections\Collection\GetListCollectionData;
use App\Packages\DataObjects\Collections\Collection\UpdateCollectionData;
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

class CollectionController extends Controller
{
    public function __construct(
        protected readonly CollectionModuleClientInterface $collectionModuleClient
    ) {
    }

    #[Get(
        path: '/api/v1/collections/collection',
        summary: 'Получить список коллекций',
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
                name: 'filter',
                description: 'Фильтрация',
                required: false,
                schema: new Schema(ref: '#/components/schemas/collections_filter_collection_data', type: 'object'),
                style: 'deepObject',
                explode: true
            ),
        ],
        responses: [
            new Response(
                response: 200,
                description: 'Список коллекций',
                content: new JsonContent(ref: '#/components/schemas/collections_collection_list_data')
            )
        ]
    )]
    public function getList(GetListCollectionData $data): CollectionListData
    {
        return $this->collectionModuleClient->getCollections($data);
    }

    #[Get(
        path: '/api/v1/collections/collection/{slug}',
        summary: 'Получить коллекцию по слагу',
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
                description: 'Коллекция',
                content: new JsonContent(ref: '#/components/schemas/collections_collection_data')
            )
        ]
    )]
    public function get(string $slug): ?CollectionData
    {
        return $this->collectionModuleClient->getCollectionBySlug($slug);
    }

    #[Post(
        path: '/api/v1/collections/collection',
        summary: 'Создать коллекцию',
        requestBody: new RequestBody(
            content: new JsonContent(ref: '#/components/schemas/collections_create_collection_data')
        ),
        tags: ['Collections'],
        responses: [
            new Response(
                response: 200,
                description: 'Созданная коллекция',
                content: new JsonContent(ref: '#/components/schemas/collections_collection_data')
            )
        ]
    )]
    public function create(CreateCollectionData $data): CollectionData
    {
        return $this->collectionModuleClient->createCollection($data);
    }

    #[Put(
        path: '/api/v1/collections/collection/{id}',
        summary: 'Обновить коллекцию по ID',
        requestBody: new RequestBody(
            content: new JsonContent(ref: '#/components/schemas/collections_update_collection_data')
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
                description: 'Обновленная коллекция',
                content: new JsonContent(ref: '#/components/schemas/collections_collection_data')
            )
        ]
    )]
    public function update(UpdateCollectionData $data): CollectionData
    {
        return $this->collectionModuleClient->updateCollection($data);
    }

    #[Delete(
        path: '/api/v1/collections/collection/{id}',
        summary: 'Удалить коллекцию по ID',
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
        $this->collectionModuleClient->deleteCollection($id);
        return \response('');
    }
}
