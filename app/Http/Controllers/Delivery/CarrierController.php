<?php

declare(strict_types=1);

namespace App\Http\Controllers\Delivery;

use App\Http\Controllers\Controller;
use App\Packages\DataObjects\Delivery\CarrierData;
use App\Packages\DataObjects\Delivery\CreateCarrierData;
use App\Packages\DataObjects\Delivery\UpdateCarrierData;
use App\Packages\ModuleClients\DeliveryModuleClientInterface;
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

class CarrierController extends Controller
{
    public function __construct(
        private readonly DeliveryModuleClientInterface $deliveryModuleClient
    ) {
    }

    #[Get(
        path: '/api/v1/delivery/carrier',
        summary: 'Получить коллекцию перевозчиков',
        tags: ['Delivery'],
        responses: [
            new Response(
                response: 200,
                description: 'Коллекция перевозчиков',
                content: new JsonContent(
                    type: 'array',
                    items: new Items(ref: '#/components/schemas/carrier_data')
                ),
            )
        ],
    )]
    public function get(): Collection
    {
        return $this->deliveryModuleClient->getCarriers();
    }

    #[Get(
        path: '/api/v1/delivery/carrier/{id}',
        summary: 'Получить перевозчика по ID',
        tags: ['Delivery'],
        parameters: [
            new PathParameter(
                name: 'id',
                description: 'ID перевозчика',
                required: true,
                schema: new Schema(type: 'integer')
            )
        ],
        responses: [
            new Response(
                response: 200,
                description: 'Перевозчик',
                content: new JsonContent(ref: '#/components/schemas/carrier_data'),
            ),
        ],
    )]
    public function getById(int $id): CarrierData
    {
        return $this->deliveryModuleClient->getCarrierById($id);
    }

    #[Post(
        path: '/api/v1/delivery/carrier',
        summary: 'Создать перевозчика',
        requestBody: new RequestBody(
            content: new JsonContent(ref: '#/components/schemas/create_carrier_data')
        ),
        tags: ['Delivery'],
        responses: [
            new Response(
                response: 200,
                description: 'Созданный перевозчик',
                content: new JsonContent(ref: '#/components/schemas/carrier_data')
            )
        ],
    )]
    public function create(CreateCarrierData $data): CarrierData
    {
        return $this->deliveryModuleClient->createCarrier($data);
    }

    #[Put(
        path: '/api/v1/delivery/carrier',
        summary: 'Обновить перевозчика',
        requestBody: new RequestBody(
            content: new JsonContent(ref: '#/components/schemas/update_carrier_data')
        ),
        tags: ['Delivery'],
        responses: [
            new Response(
                response: 200,
                description: 'Обновленный перевозчик',
                content: new JsonContent(ref: '#/components/schemas/carrier_data')
            )
        ],
    )]
    public function update(UpdateCarrierData $data): CarrierData
    {
        return $this->deliveryModuleClient->updateCarrier($data);
    }

    #[Delete(
        path: '/api/v1/delivery/carrier/{id}',
        summary: 'Удалить перевозчика по ID',
        tags: ['Delivery'],
        parameters: [
            new PathParameter(
                name: 'id',
                description: 'ID перевозчика',
                required: true,
                schema: new Schema(type: 'integer')
            )
        ],
        responses: [
            new Response(response: 200, description: 'AOK')
        ]
    )]
    public function delete(int $id): void
    {
        $this->deliveryModuleClient->deleteCarrier($id);
    }
}
