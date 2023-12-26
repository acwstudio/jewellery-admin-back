<?php

declare(strict_types=1);

namespace App\Http\Controllers\Delivery;

use App\Http\Controllers\Controller;
use App\Packages\DataObjects\Checkout\Summary\GetSummaryData;
use App\Packages\DataObjects\Checkout\Summary\SummaryData;
use App\Packages\DataObjects\Delivery\CurrierDeliveryData;
use App\Packages\DataObjects\Delivery\CreateCurrierDeliveryData;
use App\Packages\ModuleClients\DeliveryModuleClientInterface;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\QueryParameter;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Schema;

class CurrierDeliveryController extends Controller
{
    public function __construct(
        private readonly DeliveryModuleClientInterface $deliveryModuleClient
    ) {
    }

    #[Post(
        path: '/api/v1/delivery/currier',
        summary: 'Создать курьерскую доставку',
        requestBody: new RequestBody(
            content: new JsonContent(ref: '#/components/schemas/create_currier_delivery_data')
        ),
        tags: ['Delivery'],
        responses: [
            new Response(
                response: 200,
                description: 'Объект курьерской доставки',
                content: new JsonContent(
                    ref: '#/components/schemas/currier_delivery_data',
                    type: 'object'
                ),
            )
        ],
    )]
    public function create(CreateCurrierDeliveryData $data): CurrierDeliveryData
    {
        return $this->deliveryModuleClient->createCurrierDelivery($data);
    }
}
