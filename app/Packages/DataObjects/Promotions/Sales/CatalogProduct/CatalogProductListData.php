<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Promotions\Sales\CatalogProduct;

use App\Packages\DataObjects\Catalog\Product\ProductData as CatalogProductData;
use App\Packages\DataObjects\Common\List\ListData;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;

#[Schema(
    schema: 'promotions_sales_catalog_product_list_data',
    description: 'Список каталожных товаров из Акции',
    required: ['items'],
    type: 'object'
)]
class CatalogProductListData extends ListData
{
    #[Property(
        property: 'items',
        type: 'array',
        items: new Items(ref: '#/components/schemas/catalog_product_data')
    )]
    #[DataCollectionOf(CatalogProductData::class)]
    /** @phpstan-ignore-next-line */
    public readonly DataCollection $items;

    public static function fromPaginator(LengthAwarePaginator $paginator): self
    {
        return new self(
            CatalogProductData::collection([]),
            self::getPaginationData($paginator)
        );
    }
}
