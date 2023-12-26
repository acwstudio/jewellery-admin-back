<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Promotions\Promocode;

use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;

class UpdatePromocodeUsage extends Data
{
    public function __construct(
        #[MapOutputName('shop_cart_token')]
        public readonly string $shopCartToken,
        #[MapOutputName('user_id')]
        public readonly string $userId,
        #[MapOutputName('is_active')]
        public readonly bool $isActive = true,
        #[MapOutputName('order_id')]
        public readonly ?int $orderId = null
    ) {
    }
}
