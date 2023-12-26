<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Live\LiveProduct;

use App\Modules\Live\Models\LiveProductListItem;
use App\Packages\DataObjects\Common\List\ListData;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;

#[Schema(
    schema: 'live_live_product_list_item_list_data',
    description: 'Список элементов Прямого эфира',
    required: ['items'],
    type: 'object'
)]
class LiveProductListItemListData extends ListData
{
    #[Property(
        property: 'items',
        type: 'array',
        items: new Items(ref: '#/components/schemas/live_live_product_list_item_data')
    )]
    #[DataCollectionOf(LiveProductListItemData::class)]
    /** @phpstan-ignore-next-line */
    public readonly DataCollection $items;

    public static function fromPaginator(LengthAwarePaginator $paginator): self
    {
        $items = array_map(
            fn (LiveProductListItem $model) => LiveProductListItemData::fromModel($model),
            $paginator->items()
        );

        return new self(
            LiveProductListItemData::collection($items),
            self::getPaginationData($paginator)
        );
    }
}
