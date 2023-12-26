<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Orders\Product;

use App\Packages\DataTransformers\MoneyTransformer;
use Money\Money;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;

#[Schema(schema: 'orders_order_product_data', type: 'object')]
class OrderProductData extends Data
{
    public function __construct(
        #[Property('image', type: 'string')]
        public readonly string $image,
        #[Property('name', type: 'string')]
        public readonly string $name,
        #[Property('size', type: 'string', nullable: true)]
        public readonly ?string $size,
        #[WithTransformer(MoneyTransformer::class)]
        #[Property('regular_price', type: 'string')]
        public readonly Money $regular_price,
        #[WithTransformer(MoneyTransformer::class)]
        #[Property('promo_price', type: 'string', nullable: true)]
        public readonly ?Money $promo_price,
        #[Property('count', type: 'int')]
        public readonly int $count,
        #[Property('slug', type: 'string')]
        public readonly string $slug
    ) {
    }
}
