<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Orders\CreateOrder;

use App\Modules\Payment\Enums\PaymentTypeEnum;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;

#[Schema(schema: 'create_order_data', type: 'object')]
class CreateOrderData extends Data
{
    public function __construct(
        #[Property('delivery', ref: '#/components/schemas/create_order_delivery_data', type: 'object')]
        public readonly CreateOrderDeliveryData $delivery,
        #[MapName('personal_data')]
        #[Property('personal_data', ref: '#/components/schemas/create_order_personal_data', type: 'object')]
        public readonly CreateOrderPersonalData $personalData,
        #[MapName('payment_type')]
        #[Property('payment_type', ref: '#/components/schemas/payment_type_enum', type: 'object')]
        public readonly PaymentTypeEnum $paymentType,
        #[Property('comment', type: 'string', nullable: TRUE)]
        public readonly ?string $comment = null,
    ) {
    }
}
