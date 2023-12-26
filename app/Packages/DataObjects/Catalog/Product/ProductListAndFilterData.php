<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Catalog\Product;

use App\Modules\Catalog\Models\Product;
use App\Packages\DataObjects\Catalog\Filter\FilterProductData;
use App\Packages\DataObjects\Common\Pagination\PaginationData;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

#[Schema(
    schema: 'catalog_product_list_and_filter_data',
    description: 'Коллекция продуктов и фильтр',
    required: ['items', 'pagination'],
    type: 'object'
)]
class ProductListAndFilterData extends Data
{
    public function __construct(
        #[Property(
            property: 'items',
            type: 'array',
            items: new Items(ref: '#/components/schemas/catalog_product_data')
        )]
        #[DataCollectionOf(ProductData::class)]
        public readonly DataCollection $items,
        #[Property(property: 'pagination', ref: '#/components/schemas/pagination_data')]
        public readonly PaginationData $pagination,
        #[Property(property: 'filter', ref: '#/components/schemas/catalog_filter_product_data', nullable: true)]
        public readonly ?FilterProductData $filter = null
    ) {
    }

    public static function fromPaginatorAndFilter(
        LengthAwarePaginator $paginator,
        array $wishlist = [],
        ?FilterProductData $filterProductData = null
    ): self {
        $items = array_map(
            fn (Product $product) => ProductData::fromModel($product, wishlist: $wishlist),
            $paginator->items()
        );

        return new self(
            ProductData::collection($items),
            self::getPaginationData($paginator),
            $filterProductData
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
