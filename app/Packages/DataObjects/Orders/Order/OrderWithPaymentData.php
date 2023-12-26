<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Orders\Order;

use App\Packages\DataObjects\Promotions\Promocode\PromocodeData;
use App\Packages\DataTransformers\MoneyTransformer;
use Money\Money;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;

#[Schema(schema: 'orders_order_with_payment_data', type: 'object')]
class OrderWithPaymentData extends Data
{
    public function __construct(
        #[Property('id', type: 'integer')]
        public readonly int $id,
        #[WithTransformer(MoneyTransformer::class)]
        #[Property('summary', type: 'string')]
        public readonly Money $summary,
        #[Property('delivery', ref: '#/components/schemas/order_delivery_data', type: 'object')]
        public readonly OrderDeliveryData $delivery,
        #[MapName('personal_data')]
        #[Property('personal_data', ref: '#/components/schemas/order_personal_data', type: 'object')]
        public readonly OrderPersonalData $personalData,
        #[Property('paymentUrl', type: 'string', nullable: true)]
        #[MapName('payment_url')]
        public readonly ?string $paymentUrl = null,
        #[Property(
            property: 'promocode',
            ref: '#/components/schemas/promotions_promocode_data',
            type: 'object',
            nullable: true
        )]
        public readonly ?PromocodeData $promocode = null,
        #[Property('shop_cart_token', type: 'string', nullable: true)]
        #[MapName('shop_cart_token')]
        public readonly ?string $shopCartToken = null,
    ) {
    }
}
