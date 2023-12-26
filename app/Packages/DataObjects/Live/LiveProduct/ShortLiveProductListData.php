<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Live\LiveProduct;

use App\Modules\Live\Models\LiveProduct;
use App\Packages\DataObjects\Common\List\ListData;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;

#[Schema(
    schema: 'live_short_live_product_list_data',
    description: 'Список продуктов Прямого эфира',
    required: ['items'],
    type: 'object'
)]
class ShortLiveProductListData extends ListData
{
    #[Property(
        property: 'items',
        type: 'array',
        items: new Items(ref: '#/components/schemas/live_short_live_product_data')
    )]
    #[DataCollectionOf(ShortLiveProductData::class)]
    /** @phpstan-ignore-next-line */
    public readonly DataCollection $items;

    public static function fromPaginator(LengthAwarePaginator $paginator): self
    {
        $items = array_map(
            fn (LiveProduct $liveProduct) => ShortLiveProductData::fromModel($liveProduct),
            $paginator->items()
        );

        return new self(
            ShortLiveProductData::collection($items),
            self::getPaginationData($paginator)
        );
    }
}
