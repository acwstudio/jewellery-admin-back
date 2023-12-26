<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Payment;

use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(schema: 'acquiring_payment_data', type: 'object')]
class AcquiringPaymentData extends Data
{
    public function __construct(
        #[Property('url', type: 'string')]
        public readonly string $url,
        #[Property('payment_id', type: 'integer')]
        public readonly int $payment_id,
    ) {
    }
}
