<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Catalog\Product;

use App\Modules\Catalog\Models\Product;
use App\Packages\DataObjects\Common\List\ListData;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use OpenSearch\ScoutDriverPlus\Decorators\Hit;
use OpenSearch\ScoutDriverPlus\Paginator;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;

#[Schema(
    schema: 'catalog_product_list_data',
    description: 'Коллекция продуктов',
    required: ['items'],
    type: 'object'
)]
class ProductListData extends ListData
{
    #[Property(property: 'items', type: 'array', items: new Items(ref: '#/components/schemas/catalog_product_data'))]
    #[DataCollectionOf(ProductData::class)]
    /** @phpstan-ignore-next-line */
    public readonly DataCollection $items;

    public static function fromPaginator(
        LengthAwarePaginator $paginator,
        array $wishlist = [],
        array $liveIds = [],
        bool $isFull = false,
    ): self {
        $items = array_map(
            fn (Product $product) => ProductData::fromModel(
                product: $product,
                isFullData: $isFull,
                wishlist: $wishlist,
                liveIds: $liveIds
            ),
            $paginator->items()
        );

        return new self(
            ProductData::collection($items),
            self::getPaginationData($paginator)
        );
    }

    public static function fromOpenSearch(
        Paginator $paginator,
        array $wishlist = [],
        array $liveIds = [],
        bool $isFull = false,
    ): self {
        $data = $paginator->items();

        $items = array_map(
            fn(Hit $product) => ProductData::customFromArray(
                product: $product->raw()['_source'],
                isFullData: $isFull,
                wishlist: $wishlist,
                liveIds: $liveIds
            ),
            $data
        );

        return new self(
            ProductData::collection($items),
            self::getPaginationData($paginator),
        );
    }
}
