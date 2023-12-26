<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Promotions\Filter;

use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(schema: 'promotions_filter_promocode_usage_data', type: 'object')]
class FilterPromocodeUsageData extends Data
{
    public function __construct(
        #[Property(property: 'promotion_benefit_id', type: 'integer', nullable: true)]
        public readonly ?int $promotion_benefit_id = null,
        #[Property(property: 'shop_cart_token', type: 'string', nullable: true)]
        public readonly ?string $shop_cart_token = null,
        #[Property(property: 'is_active', type: 'boolean', nullable: true)]
        public readonly ?bool $is_active = null,
        #[Property(property: 'order_id', type: 'integer', nullable: true)]
        public readonly ?int $order_id = null,
    ) {
    }
}
