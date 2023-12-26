<?php

declare(strict_types=1);

namespace App\Http\Controllers\Promotions;

use App\Packages\DataObjects\Common\Response\SuccessData;
use App\Packages\DataObjects\Promotions\ApplyPromocodeData;
use App\Packages\ModuleClients\PromotionsModuleClientInterface;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response;

class PromocodeController
{
    public function __construct(
        private readonly PromotionsModuleClientInterface $promotionsModuleClient,
    ) {
    }

    #[Post(
        path: '/api/v1/promotions/promocode/apply',
        summary: 'Применить промокод',
        requestBody: new RequestBody(
            content: new JsonContent(ref: '#/components/schemas/apply_promocode_data')
        ),
        tags: ['Promotions'],
        responses: [
            new Response(
                response: 200,
                description: 'Промокод применен',
                content: new JsonContent(ref: '#/components/schemas/success_data')
            ),
        ],
    )]
    public function apply(ApplyPromocodeData $data): SuccessData
    {
        $this->promotionsModuleClient->applyPromocode($data->promocode);
        return new SuccessData();
    }

    #[Post(
        path: '/api/v1/promotions/promocode/cancel',
        summary: 'Отменить промокод',
        tags: ['Promotions'],
        responses: [
            new Response(response: 200, description: 'AOK')
        ]
    )]
    public function cancel(): void
    {
        $this->promotionsModuleClient->cancelPromocode();
    }
}
