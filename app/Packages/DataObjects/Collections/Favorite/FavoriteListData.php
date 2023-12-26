<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Collections\Favorite;

use App\Modules\Collections\Models\Favorite;
use App\Packages\DataObjects\Common\List\ListData;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;

#[Schema(
    schema: 'collections_favorite_list_data',
    description: 'Список избранных коллекций',
    required: ['items'],
    type: 'object'
)]
class FavoriteListData extends ListData
{
    #[Property(
        property: 'items',
        type: 'array',
        items: new Items(ref: '#/components/schemas/collections_favorite_data')
    )]
    #[DataCollectionOf(FavoriteData::class)]
    /** @phpstan-ignore-next-line */
    public readonly DataCollection $items;

    public static function fromPaginator(LengthAwarePaginator $paginator): self
    {
        $items = array_map(
            fn (Favorite $favorite) => FavoriteData::fromModel($favorite),
            $paginator->items()
        );

        return new self(
            FavoriteData::collection($items),
            self::getPaginationData($paginator)
        );
    }
}
