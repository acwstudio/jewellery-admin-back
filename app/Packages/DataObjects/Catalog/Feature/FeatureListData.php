<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Catalog\Feature;

use App\Modules\Catalog\Models\Feature;
use App\Packages\DataObjects\Common\List\ListData;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;

#[Schema(
    schema: 'catalog_feature_list_data',
    description: 'Список свойств',
    required: ['items'],
    type: 'object'
)]
class FeatureListData extends ListData
{
    #[Property(
        property: 'items',
        type: 'array',
        items: new Items(ref: '#/components/schemas/catalog_feature_data')
    )]
    #[DataCollectionOf(FeatureData::class)]
    /** @phpstan-ignore-next-line */
    public readonly DataCollection $items;

    public static function fromPaginator(LengthAwarePaginator $paginator): self
    {
        $items = array_map(
            fn (Feature $model) => FeatureData::fromModel($model),
            $paginator->items()
        );

        return new self(
            FeatureData::collection($items),
            self::getPaginationData($paginator)
        );
    }
}
