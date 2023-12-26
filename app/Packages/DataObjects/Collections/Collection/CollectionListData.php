<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Collections\Collection;

use App\Modules\Collections\Models\Collection;
use App\Packages\DataObjects\Common\List\ListData;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;

#[Schema(
    schema: 'collections_collection_list_data',
    description: 'Список коллекции',
    required: ['items'],
    type: 'object'
)]
class CollectionListData extends ListData
{
    #[Property(
        property: 'items',
        type: 'array',
        items: new Items(ref: '#/components/schemas/collections_collection_data')
    )]
    #[DataCollectionOf(CollectionData::class)]
    /** @phpstan-ignore-next-line */
    public readonly DataCollection $items;

    public static function fromPaginator(LengthAwarePaginator $paginator): self
    {
        $items = array_map(
            fn (Collection $collection) => CollectionData::fromModel($collection),
            $paginator->items()
        );

        return new self(
            CollectionData::collection($items),
            self::getPaginationData($paginator)
        );
    }
}
