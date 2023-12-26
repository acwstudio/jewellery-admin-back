<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Live\LiveProduct;

use App\Packages\DataObjects\Catalog\Product\ProductData;
use App\Packages\DataObjects\Common\List\ListData;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;

#[Schema(
    schema: 'live_live_product_list_data',
    description: 'Список продуктов Прямого эфира',
    required: ['items'],
    type: 'object'
)]
class LiveProductListData extends ListData
{
    #[Property(
        property: 'items',
        type: 'array',
        items: new Items(ref: '#/components/schemas/catalog_product_data')
    )]
    #[DataCollectionOf(ProductData::class)]
    /** @phpstan-ignore-next-line */
    public readonly DataCollection $items;

    public static function fromPaginator(LengthAwarePaginator $paginator): self
    {
        return new self(
            ProductData::collection([]),
            self::getPaginationData($paginator)
        );
    }
}
