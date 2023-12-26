<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Orders\Item;

use App\Packages\DataObjects\Common\List\ListData;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;

#[Schema(schema: 'orders_order_item_list_data', type: 'object')]
class OrderItemListData extends ListData
{
    #[Property(
        property: 'items',
        type: 'array',
        items: new Items(ref: '#/components/schemas/orders_order_item_data')
    )]
    #[DataCollectionOf(OrderItemData::class)]
    /** @phpstan-ignore-next-line */
    public readonly DataCollection $items;

    public static function fromPaginator(LengthAwarePaginator $paginator): self
    {
        return new self(
            OrderItemData::collection([]),
            self::getPaginationData($paginator)
        );
    }
}
