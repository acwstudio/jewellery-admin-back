<?php

declare(strict_types=1);

namespace App\Http\Controllers\Delivery;

use App\Http\Controllers\Controller;
use App\Packages\DataObjects\Delivery\CreatePvzData;
use App\Packages\DataObjects\Delivery\GetPvz\GetPvzData;
use App\Packages\DataObjects\Delivery\PvzData;
use App\Packages\DataObjects\Delivery\UpdatePvzData;
use App\Packages\ModuleClients\DeliveryModuleClientInterface;
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

class PvzController extends Controller
{
    public function __construct(
        private readonly DeliveryModuleClientInterface $deliveryModuleClient
    ) {
    }

    #[Get(
        path: '/api/v1/delivery/pvz',
        summary: 'Получить коллекцию ПВЗ',
        tags: ['Delivery'],
        parameters: [
            new QueryParameter(
                name: 'filter',
                description: 'Фильтр ПВЗ',
                required: false,
                schema: new Schema(ref: '#/components/schemas/get_pvz_filter_data', type: 'object'),
                style: 'deepObject',
                explode: true
            )
        ],
        responses: [
            new Response(
                response: 200,
                description: 'Коллекция ПВЗ',
                content: new JsonContent(
                    type: 'array',
                    items: new Items(ref: '#/components/schemas/pvz_data')
                ),
            )
        ],
    )]
    public function get(GetPvzData $data): Collection
    {
        return $this->deliveryModuleClient->getPvz($data);
    }


    #[Get(
        path: '/api/v1/delivery/pvz/{id}',
        summary: 'Получить ПВЗ по ID',
        tags: ['Delivery'],
        parameters: [
            new PathParameter(
                name: 'id',
                description: 'ID ПВЗ',
                required: true,
                schema: new Schema(type: 'integer')
            )
        ],
        responses: [
            new Response(
                response: 200,
                description: 'ПВЗ',
                content: new JsonContent(ref: '#/components/schemas/pvz_data'),
            ),
        ],
    )]
    public function getById(int $id): PvzData
    {
        return $this->deliveryModuleClient->getPvzById($id);
    }

    #[Post(
        path: '/api/v1/delivery/pvz',
        summary: 'Создать ПВЗ',
        requestBody: new RequestBody(
            content: new JsonContent(ref: '#/components/schemas/create_pvz_data')
        ),
        tags: ['Delivery'],
        responses: [
            new Response(
                response: 200,
                description: 'Созданный ПВЗ',
                content: new JsonContent(ref: '#/components/schemas/pvz_data')
            )
        ],
    )]
    public function create(CreatePvzData $data): PvzData
    {
        return $this->deliveryModuleClient->createPvz($data);
    }

    #[Put(
        path: '/api/v1/delivery/pvz',
        summary: 'Обновить ПВЗ',
        requestBody: new RequestBody(
            content: new JsonContent(ref: '#/components/schemas/update_pvz_data')
        ),
        tags: ['Delivery'],
        responses: [
            new Response(
                response: 200,
                description: 'Обновленный ПВЗ',
                content: new JsonContent(ref: '#/components/schemas/pvz_data')
            )
        ],
    )]
    public function update(UpdatePvzData $data): PvzData
    {
        return $this->deliveryModuleClient->updatePvz($data);
    }

    #[Delete(
        path: '/api/v1/delivery/pvz/{id}',
        summary: 'Удалить ПВЗ по ID',
        tags: ['Delivery'],
        parameters: [
            new PathParameter(
                name: 'id',
                description: 'ID ПВЗ',
                required: true,
                schema: new Schema(type: 'integer')
            )
        ],
        responses: [
            new Response(response: 200, description: 'AOK')
        ]
    )]
    public function delete(int $id): \Illuminate\Http\Response
    {
        $this->deliveryModuleClient->deletePvz($id);
        return \response('');
    }
}
