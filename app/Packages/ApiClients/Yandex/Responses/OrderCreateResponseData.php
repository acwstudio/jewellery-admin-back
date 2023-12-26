<?php

declare(strict_types=1);

namespace App\Packages\ApiClients\Yandex\Responses;

use Spatie\LaravelData\Data;

class OrderCreateResponseData extends Data
{
    public function __construct(
        public readonly string $checkout_url,
        public readonly string $order_id
    ) {
    }
}
