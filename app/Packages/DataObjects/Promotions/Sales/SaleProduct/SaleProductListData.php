<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Promotions\Sales\SaleProduct;

use App\Modules\Promotions\Modules\Sales\Models\SaleProduct;
use App\Packages\DataObjects\Common\List\ListData;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;

#[Schema(
    schema: 'promotions_sales_sale_product_list_data',
    description: 'Список акционных товаров',
    required: ['items'],
    type: 'object'
)]
class SaleProductListData extends ListData
{
    #[Property(
        property: 'items',
        type: 'array',
        items: new Items(ref: '#/components/schemas/promotions_sales_sale_product_data')
    )]
    #[DataCollectionOf(SaleProductData::class)]
    /** @phpstan-ignore-next-line */
    public readonly DataCollection $items;

    public static function fromPaginator(LengthAwarePaginator $paginator): self
    {
        $items = array_map(
            fn (SaleProduct $model) => SaleProductData::fromModel($model),
            $paginator->items()
        );

        return new self(
            SaleProductData::collection($items),
            self::getPaginationData($paginator)
        );
    }
}
