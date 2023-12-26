<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Promotions\Promocode\Price;

use App\Packages\DataObjects\Promotions\Promocode\Price\Filter\FilterPromocodePriceData;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'promotions_get_promocode_price_list_data',
    description: 'Получение цен промокодов',
    type: 'object'
)]
class GetPromocodePriceListData extends Data
{
    public function __construct(
        #[Property(
            property: 'filter',
            ref: '#/components/schemas/promotions_filter_promocode_price_data',
            nullable: true
        )]
        public readonly ?FilterPromocodePriceData $filter = null
    ) {
    }
}
