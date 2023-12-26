<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Orders\Messages\CreateOrder;

use App\Packages\DataTransformers\MoneyTransformer;
use Money\Money;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;

class CreateOrderMessageProductData extends Data
{
    public function __construct(
        #[MapName('product_guid')]
        public readonly string $externalId,
        public readonly string $sku,
        #[WithTransformer(MoneyTransformer::class)]
        public readonly Money $price,
        public readonly int $count,
        #[MapName('curs')]
        public readonly string $conversionRate,
        #[WithTransformer(MoneyTransformer::class)]
        public readonly Money $amount,
        #[WithTransformer(MoneyTransformer::class)]
        public readonly ?Money $discount = null,
        #[MapName('sale_id')]
        public readonly ?string $saleId = null,
        public readonly ?string $size = null,
    ) {
    }
}
