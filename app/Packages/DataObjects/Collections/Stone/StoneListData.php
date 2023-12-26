<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Collections\Stone;

use App\Modules\Collections\Models\Stone;
use App\Packages\DataObjects\Common\List\ListData;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;

#[Schema(
    schema: 'collections_stone_list_data',
    description: 'Список вставок (камней) коллекций',
    required: ['items'],
    type: 'object'
)]
class StoneListData extends ListData
{
    #[Property(property: 'items', type: 'array', items: new Items(ref: '#/components/schemas/collections_stone_data'))]
    #[DataCollectionOf(StoneData::class)]
    /** @phpstan-ignore-next-line */
    public readonly DataCollection $items;

    public static function fromPaginator(LengthAwarePaginator $paginator): self
    {
        $items = array_map(
            fn (Stone $stone) => StoneData::fromModel($stone),
            $paginator->items()
        );

        return new self(
            StoneData::collection($items),
            self::getPaginationData($paginator)
        );
    }
}
