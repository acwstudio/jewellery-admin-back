<?php

declare(strict_types=1);

namespace App\Http\Controllers\Checkout;

use App\Http\Controllers\Controller;
use App\Packages\DataObjects\Checkout\CalculateData;
use App\Packages\DataObjects\Checkout\CheckoutData;
use App\Packages\DataObjects\Checkout\Summary\GetSummaryData;
use App\Packages\DataObjects\Checkout\Summary\SummaryData;
use App\Packages\ModuleClients\CheckoutModuleClientInterface;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\QueryParameter;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Schema;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly CheckoutModuleClientInterface $checkoutModuleClient
    ) {
    }

    #[Get(
        path: '/api/v1/checkout',
        summary: 'Получить Чекаут',
        tags: ['Checkout'],
        responses: [
            new Response(
                response: 200,
                description: 'Чекаут',
                content: new JsonContent(
                    type: 'array',
                    items: new Items(ref: '#/components/schemas/checkout_data')
                ),
            )
        ],
    )]
    public function get(): CheckoutData
    {
        return $this->checkoutModuleClient->getCheckout();
    }

    #[Get(
        path: '/api/v1/checkout/calculate',
        summary: 'Калькулятор заказа',
        tags: ['Checkout'],
        responses: [
            new Response(
                response: 200,
                description: 'Данные результата подсчёт',
                content: new JsonContent(
                    type: 'array',
                    items: new Items(ref: '#/components/schemas/calculate_data')
                ),
            )
        ],
    )]
    public function calculate(): CalculateData
    {
        return $this->checkoutModuleClient->calculate();
    }

    #[Get(
        path: '/api/v1/checkout/summary',
        summary: 'Получить итоговую стоимость',
        tags: ['Checkout'],
        parameters: [
            new QueryParameter(
                name: 'delivery',
                description: 'Параметры доставки',
                required: false,
                schema: new Schema(ref: '#/components/schemas/get_summary_delivery_data', type: 'object'),
                style: 'deepObject',
                explode: true
            )
        ],
        responses: [
            new Response(
                response: 200,
                description: 'Итоговая стоимость',
                content: new JsonContent(
                    type: 'array',
                    items: new Items(ref: '#/components/schemas/checkout_summary_data')
                ),
            )
        ],
    )]
    public function summary(GetSummaryData $data): SummaryData
    {
        return $this->checkoutModuleClient->getSummary($data);
    }
}
