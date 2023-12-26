<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Orders\Item;

use App\Packages\DataObjects\Orders\Product\OrderProductData;
use App\Packages\DataTransformers\MoneyTransformer;
use App\Packages\Enums\Orders\DeliveryType;
use App\Packages\Enums\Orders\OrderStatusEnum;
use Carbon\Carbon;
use Money\Money;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

#[Schema(schema: 'orders_order_item_data', type: 'object')]
class OrderItemData extends Data
{
    public function __construct(
        #[Property('date', type: 'datetime')]
        public readonly Carbon $date,
        #[Property('order_id', type: 'integer')]
        public readonly int $order_id,
        #[Property('delivery_type', ref: '#/components/schemas/delivery_type_enum')]
        public readonly DeliveryType $delivery_type,
        #[Property('delivery_address')]
        public readonly string $delivery_address,
        #[WithTransformer(MoneyTransformer::class)]
        #[Property('full_price', type: 'string')]
        public readonly Money $full_price,
        #[Property('status', ref: '#/components/schemas/order_status_enum')]
        public readonly OrderStatusEnum $status,
        #[Property(
            property: 'products',
            type: 'array',
            items: new Items(ref: '#/components/schemas/orders_order_product_data')
        )]
        #[DataCollectionOf(OrderProductData::class)]
        public readonly DataCollection $products,
        #[Property('products_count', type: 'integer')]
        public readonly int $products_count,
        #[WithTransformer(MoneyTransformer::class)]
        #[Property('discount_sale', type: 'string')]
        public readonly Money $discount_sale,
        #[WithTransformer(MoneyTransformer::class)]
        #[Property('delivery_price', type: 'string')]
        public readonly Money $delivery_price
    ) {
    }
}
