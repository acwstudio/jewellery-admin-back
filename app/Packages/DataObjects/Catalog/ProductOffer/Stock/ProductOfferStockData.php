<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Catalog\ProductOffer\Stock;

use App\Modules\Catalog\Models\ProductOfferStock;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'catalog_product_offer_stock_data',
    description: 'Остаток торгового предложения продукта',
    required: ['id', 'count'],
    type: 'object'
)]
class ProductOfferStockData extends Data
{
    public function __construct(
        #[Property(property: 'id', type: 'integer')]
        public readonly int $id,
        #[Property(property: 'count', type: 'integer')]
        public readonly int $count
    ) {
    }

    public static function fromModel(ProductOfferStock $productOfferStock): self
    {
        return new self(
            $productOfferStock->id,
            $productOfferStock->count
        );
    }
}
