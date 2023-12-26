<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Collections\CollectionProduct;

use App\Modules\Collections\Models\CollectionProductListItem;
use App\Packages\DataObjects\Common\List\ListData;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;

#[Schema(
    schema: 'collections_collection_product_list_item_list_data',
    description: 'Список коллекции',
    required: ['items'],
    type: 'object'
)]
class CollectionProductListItemListData extends ListData
{
    #[Property(
        property: 'items',
        type: 'array',
        items: new Items(ref: '#/components/schemas/collections_collection_product_list_item_data')
    )]
    #[DataCollectionOf(CollectionProductListItemData::class)]
    /** @phpstan-ignore-next-line */
    public readonly DataCollection $items;

    public static function fromPaginator(LengthAwarePaginator $paginator): self
    {
        $items = array_map(
            fn (CollectionProductListItem $model) => CollectionProductListItemData::fromModel($model),
            $paginator->items()
        );

        return new self(
            CollectionProductListItemData::collection($items),
            self::getPaginationData($paginator)
        );
    }
}
