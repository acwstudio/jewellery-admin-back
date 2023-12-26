<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Catalog\ProductOffer;

use App\Modules\Catalog\Models\ProductOffer;
use App\Modules\Catalog\Models\ProductOfferPrice;
use App\Modules\Catalog\Models\ProductOfferStock;
use App\Packages\DataObjects\Catalog\ProductOffer\Price\ProductOfferPriceData;
use App\Packages\Enums\Catalog\OfferPriceTypeEnum;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

#[Schema(
    schema: 'catalog_product_offer_data',
    description: 'Торговое предложение продукта',
    required: ['id', 'size', 'count', 'price', 'prices'],
    type: 'object'
)]
class ProductOfferData extends Data
{
    public function __construct(
        #[Property(property: 'id', type: 'integer')]
        public readonly int $id,
        #[Property(property: 'product_id', type: 'integer')]
        public readonly int $product_id,
        #[Property(property: 'size', description: 'Размер', type: 'string', nullable: true)]
        public readonly ?string $size,
        #[Property(property: 'weight', description: 'Вес', type: 'string', nullable: true)]
        public readonly ?string $weight,
        #[Property(property: 'count', description: 'Количество', type: 'integer')]
        public readonly int $count,
        #[Property(
            property: 'prices',
            type: 'array',
            items: new Items(ref: '#/components/schemas/catalog_product_offer_price_data')
        )]
        #[DataCollectionOf(ProductOfferPriceData::class)]
        public readonly DataCollection $prices
    ) {
    }

    public static function fromModel(ProductOffer $productOffer, ?bool $isActive = null, bool $isLive = false): self
    {
        $currentProductOfferStock = self::getCurrentProductOfferStock($productOffer);
        $productOfferPriceDataItems = self::getProductOfferPriceDataCollection($productOffer, $isActive, $isLive);

        return new self(
            $productOffer->id,
            $productOffer->product_id,
            $productOffer->size,
            $productOffer->weight,
            $currentProductOfferStock?->count ?? 0,
            ProductOfferPriceData::collection($productOfferPriceDataItems->flatten())
        );
    }

    public static function customFromArray(array $productOffer, ?bool $isActive = null, bool $isLive = false): self
    {
        $currentProductOfferStock = self::getCurrentProductOfferStockArray(
            $productOffer['product_offer_stocks'] ?? []
        );

        $productOfferPriceDataItems = self::getProductOfferPriceDataCollectionFromArray(
            $productOffer['product_offer_prices'] ?? [],
            $isActive,
            $isLive
        );

        return new self(
            $productOffer['id'],
            $productOffer['product_id'],
            $productOffer['size'] ?? null,
            $productOffer['weight'] ?? null,
            $currentProductOfferStock['count'] ?? 0,
            ProductOfferPriceData::collection($productOfferPriceDataItems->flatten())
        );
    }

    private static function getProductOfferPriceDataCollection(
        ProductOffer $productOffer,
        ?bool $isActive = null,
        bool $isLive = false,
    ): Collection {
        $collection = $productOffer->productOfferPrices->sortByDesc('id');
        $collection = $collection->where('type', '!=', OfferPriceTypeEnum::EMPLOYEE);

        if (!empty($isActive)) {
            $collection = $collection->where('is_active', '=', $isActive);
        }

        if (!$isLive) {
            $collection = $collection->where('type', '!=', OfferPriceTypeEnum::LIVE);
        }

        return $collection->map(
            fn (ProductOfferPrice $price) => ProductOfferPriceData::fromModel($price)
        );
    }

    private static function getCurrentProductOfferStock(ProductOffer $productOffer): ?ProductOfferStock
    {
        /** @var ProductOfferStock|null $productOfferStock */
        $productOfferStock =  $productOffer->productOfferStocks
            ->where('is_current', '=', true)
            ->first();

        return $productOfferStock;
    }

    private static function getProductOfferPriceDataCollectionFromArray(
        array $productOfferPrices,
        ?bool $isActive = null,
        bool $isLive = false,
    ): Collection {
        $collection = collect($productOfferPrices)->sortByDesc('id');
        $collection = $collection->where('type', '!=', OfferPriceTypeEnum::EMPLOYEE);

        if (!empty($isActive)) {
            $collection = $collection->where('is_active', '=', $isActive);
        }

        if (!$isLive) {
            $collection = $collection->where('type', '!=', OfferPriceTypeEnum::LIVE);
        }

        return $collection->map(
            fn (array $price) => ProductOfferPriceData::customFromArray($price)
        );
    }

    private static function getCurrentProductOfferStockArray(array $productOfferStocks): array
    {
        return Arr::first(
            $productOfferStocks,
            fn (array $value) => ($value['is_current'] ?? false) === true,
            []
        );
    }
}
