<?php

declare(strict_types=1);

namespace App\Http\Controllers\Stores;

use App\Http\Controllers\Controller;
use App\Packages\DataObjects\Common\Response\SuccessData;
use App\Packages\DataObjects\Stores\CreateStoreData;
use App\Packages\DataObjects\Stores\StoreData;
use App\Packages\DataObjects\Stores\UpdateStoreData;
use App\Packages\ModuleClients\StoresModuleClientInterface;
use Illuminate\Support\Collection;
use OpenApi\Attributes\Delete;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\PathParameter;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\Put;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Schema;

class StoreController extends Controller
{
    public function __construct(
        protected readonly StoresModuleClientInterface $storeModuleClient
    ) {
    }
    #[Get(
        path: '/api/v1/shop',
        summary: 'Получить список магазинов',
        tags: ['Stores'],
        responses: [
            new Response(
                response: 200,
                description: 'Коллекция магазинов',
                content: new JsonContent(type: 'array', items: new Items(
                    ref: '#/components/schemas/store_data'
                ))
            )
        ]
    )]
    public function index(): Collection
    {
        return $this->storeModuleClient->getAllStores();
    }

    #[Get(
        path: '/api/v1/shop/{id}',
        summary: 'Получить магазин по id',
        tags: ['Stores'],
        parameters: [
            new PathParameter(
                name: 'id',
                schema: new Schema(type: 'string')
            )
        ],
        responses: [
            new Response(
                response: 200,
                description: 'Магазин',
                content: new JsonContent(ref: '#/components/schemas/store_data')
            )
        ]
    )]
    public function show(int $id): StoreData
    {
        return $this->storeModuleClient->getStoreById($id);
    }

    #[Post(
        path: '/api/v1/shop',
        summary: 'Создание магазина',
        requestBody: new RequestBody(
            content: new JsonContent(ref: '#/components/schemas/create_store_data')
        ),
        tags: ['Stores'],
        responses: [
            new Response(
                response: 200,
                description: 'Магазин',
                content: new JsonContent(ref: '#/components/schemas/store_data')
            )
        ]
    )]
    public function store(CreateStoreData $storeData): StoreData
    {
        return $this->storeModuleClient->createStore($storeData);
    }

    #[Put(
        path: '/api/v1/shop',
        summary: 'Обновление магазина',
        requestBody: new RequestBody(
            content: new JsonContent(ref: '#/components/schemas/update_store_data')
        ),
        tags: ['Stores'],
        parameters: [
            new PathParameter(
                name: 'id',
                schema: new Schema(type: 'string')
            )
        ],
        responses: [
            new Response(
                response: 200,
                description: 'Магазин',
                content: new JsonContent(ref: '#/components/schemas/store_data')
            )
        ]
    )]
    public function update(int $id, UpdateStoreData $storeData): StoreData
    {
        return $this->storeModuleClient->updateStore($id, $storeData);
    }

    #[Delete(
        path: '/api/v1/shop/{id}',
        summary: 'Удалить магазин',
        tags: ['Stores'],
        parameters: [
            new PathParameter(
                name: 'id',
                schema: new Schema(type: 'string')
            )
        ],
        responses: [
            new Response(
                response: 200,
                description: 'Успешно удалено',
                content: new JsonContent(ref: '#/components/schemas/success_data')
            )
        ]
    )]
    public function destroy(int $id): SuccessData
    {
        return $this->storeModuleClient->deleteStoreById($id);
    }

    #[Get(
        path: '/api/v1/shop/types',
        summary: 'Получить список типов магазинов',
        tags: ['Stores'],
        responses: [
            new Response(
                response: 200,
                description: 'Коллекция типов магазинов',
                content: new JsonContent(type: 'array', items: new Items(
                    ref: '#/components/schemas/store_type_data'
                ))
            )
        ]
    )]
    public function getStoreTypes(): Collection
    {
        return $this->storeModuleClient->getAllStoreTypes();
    }
}
