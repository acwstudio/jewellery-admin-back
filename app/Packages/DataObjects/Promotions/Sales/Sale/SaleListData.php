<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Promotions\Sales\Sale;

use App\Modules\Promotions\Modules\Sales\Models\Sale;
use App\Packages\DataObjects\Common\List\ListData;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;

#[Schema(
    schema: 'promotions_sales_sale_list_data',
    description: 'Список акций',
    required: ['items'],
    type: 'object'
)]
class SaleListData extends ListData
{
    #[Property(
        property: 'items',
        type: 'array',
        items: new Items(ref: '#/components/schemas/promotions_sales_sale_data')
    )]
    #[DataCollectionOf(SaleData::class)]
    /** @phpstan-ignore-next-line */
    public readonly DataCollection $items;

    public static function fromPaginator(LengthAwarePaginator $paginator): self
    {
        $items = array_map(
            fn (Sale $model) => SaleData::fromModel($model),
            $paginator->items()
        );

        return new self(
            SaleData::collection($items),
            self::getPaginationData($paginator)
        );
    }
}
