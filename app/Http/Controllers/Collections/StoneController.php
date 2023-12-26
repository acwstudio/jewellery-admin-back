<?php

declare(strict_types=1);

namespace App\Http\Controllers\Collections;

use App\Http\Controllers\Controller;
use App\Packages\DataObjects\Collections\Stone\CreateStoneData;
use App\Packages\DataObjects\Collections\Stone\GetListStoneData;
use App\Packages\DataObjects\Collections\Stone\StoneData;
use App\Packages\DataObjects\Collections\Stone\StoneListData;
use App\Packages\ModuleClients\CollectionModuleClientInterface;
use OpenApi\Attributes\Delete;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\PathParameter;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\QueryParameter;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Schema;

class StoneController extends Controller
{
    public function __construct(
        protected readonly CollectionModuleClientInterface $collectionModuleClient
    ) {
    }

    #[Get(
        path: '/api/v1/collections/stone',
        summary: 'Получить список вставок (камней) коллекции',
        tags: ['Collections'],
        parameters: [
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
                description: 'Список вставок (камней)',
                content: new JsonContent(ref: '#/components/schemas/collections_stone_list_data')
            )
        ]
    )]
    public function getList(GetListStoneData $data): StoneListData
    {
        return $this->collectionModuleClient->getStones($data);
    }

    #[Post(
        path: '/api/v1/collections/stone',
        summary: 'Создание вставки (камня) коллекции',
        requestBody: new RequestBody(
            content: new JsonContent(ref: '#/components/schemas/collections_create_stone_data')
        ),
        tags: ['Collections'],
        responses: [
            new Response(
                response: 200,
                description: 'Вставка (камень) коллекции',
                content: new JsonContent(ref: '#/components/schemas/collections_stone_data')
            )
        ]
    )]
    public function create(CreateStoneData $data): StoneData
    {
        return $this->collectionModuleClient->createStone($data);
    }

    #[Delete(
        path: '/api/v1/collections/stone/{id}',
        summary: 'Удалить файл коллекции по ID',
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
        $this->collectionModuleClient->deleteStone($id);
        return \response('');
    }
}
