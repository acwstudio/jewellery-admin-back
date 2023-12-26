<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Catalog\Product;

use App\Modules\Catalog\Models\Product;
use App\Packages\DataObjects\Common\Pagination\PaginationData;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use OpenSearch\ScoutDriverPlus\Decorators\Hit;
use OpenSearch\ScoutDriverPlus\Paginator;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

#[Schema(
    schema: 'catalog_product_item_list_data',
    description: 'Коллекция продуктовых элементов',
    required: ['items'],
    type: 'object'
)]
class ProductItemListData extends Data
{
    public function __construct(
        #[Property(
            property: 'items',
            type: 'array',
            items: new Items(ref: '#/components/schemas/catalog_product_item_data')
        )]
        #[DataCollectionOf(ProductItemData::class)]
        public readonly DataCollection $items,
        #[Property(property: 'pagination', ref: '#/components/schemas/pagination_data')]
        public readonly PaginationData $pagination
    ) {
    }

    public static function fromPaginator(
        Paginator $paginator,
        array $wishlist = []
    ): self {
        $data = $paginator->items();

        $items = array_map(
            fn (Hit $product) => ProductItemData::customFromArray(
                product: $product->raw()['_source'],
                wishlist: $wishlist
            ),
            $data
        );

        return new self(
            ProductItemData::collection($items),
            self::getPaginationData($paginator),
        );
    }

    public static function fromModelPaginator(
        LengthAwarePaginator $paginator,
        array $wishlist = []
    ): self {
        $data = $paginator->items();

        $items = array_map(
            fn(Product $product) => ProductItemData::fromModel(
                product: $product,
                wishlist: $wishlist
            ),
            $data
        );

        return new self(
            ProductItemData::collection($items),
            self::getPaginationData($paginator),
        );
    }

    private static function getPaginationData(LengthAwarePaginator $paginator): PaginationData
    {
        return new PaginationData(
            $paginator->currentPage(),
            $paginator->perPage(),
            $paginator->total(),
            $paginator->lastPage()
        );
    }
}
