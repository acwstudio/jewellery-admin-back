<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Payment;

use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;

#[Schema(schema: 'payment_request_data', type: 'object')]
class PaymentRequestData extends Data
{
    public function __construct(
        #[MapName('order_id')]
        #[Property('order_id', type: 'integer')]
        public readonly int $orderId,
        #[Property('amount', type: 'integer')]
        public readonly int $amount,
        #[Property('returnUrl', type: 'string', nullable: true)]
        public readonly ?string $returnUrl = '',
        #[Property('params', ref: '#/components/schemas/payment_request_params', type: 'object', nullable: TRUE)]
        public ?PaymentRequestParams $params = null,
    ) {
    }
}
