<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Orders\Messages\CreateOrder;

use App\Packages\DataTransformers\MoneyTransformer;
use Money\Money;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;

class CreateOrderMessageServiceData extends Data
{
    public function __construct(
        public readonly string $id,
        #[WithTransformer(MoneyTransformer::class)]
        public readonly Money $price
    ) {
    }
}
