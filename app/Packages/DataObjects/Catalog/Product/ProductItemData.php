<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Catalog\Product;

use App\Modules\Catalog\Models\Product;
use App\Packages\DataCasts\MoneyCast;
use App\Packages\DataObjects\Catalog\PreviewImage\PreviewImageData;
use App\Packages\DataTransformers\MoneyDecimalTransformer;
use Money\Money;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'catalog_product_item_data',
    description: 'Продукт',
    required: ['id', 'slug', 'name', 'preview_image', 'price', 'price_old'],
    type: 'object'
)]
class ProductItemData extends Data
{
    public function __construct(
        #[Property(property: 'id', type: 'integer')]
        public readonly int $id,
        #[Property(property: 'sku', type: 'string')]
        public readonly string $sku,
        #[Property(property: 'slug', type: 'string')]
        public readonly string $slug,
        #[Property(property: 'name', type: 'string')]
        public readonly string $name,
        #[Property(property: 'categories', type: 'array', items: new Items(type: 'integer'))]
        public readonly array $categories,
        #[Property(property: 'image', type: 'string')]
        public readonly string $image,
        #[Property(property: 'price', type: 'integer')]
        #[WithTransformer(MoneyDecimalTransformer::class)]
        #[WithCast(MoneyCast::class, isDecimal: true)]
        public readonly Money $price,
        #[Property(property: 'price_old', type: 'integer', nullable: true)]
        #[WithTransformer(MoneyDecimalTransformer::class)]
        #[WithCast(MoneyCast::class, isDecimal: true)]
        public readonly ?Money $price_old = null,
        #[Property(property: 'on_wishlist', type: 'boolean')]
        public readonly bool $on_wishlist = false
    ) {
    }

    public static function fromModel(Product $product, array $wishlist = []): self
    {
        $product = $product->toSearchableArray();
        return self::customFromArray($product, $wishlist);
    }

    public static function customFromArray(array $product, array $wishlist = []): self
    {
        $onWishlist = false;
        if (isset($product['id']) && in_array($product['id'], $wishlist, true)) {
            $onWishlist = true;
        }

        $prices = self::getPrices($product);

        return new self(
            id: $product['id'],
            sku: $product['sku'],
            slug: $product['slug'],
            name: $product['name'],
            categories: self::getCategoryIdsByArray($product),
            image: self::getPreviewImageUrl($product),
            price: $prices['price'],
            price_old: $prices['price_old'],
            on_wishlist: $onWishlist
        );
    }

    private static function getPreviewImageUrl(array $product): string
    {
        $imageUrlsCollection = collect($product['image_urls']);
        $mainImageUrl = $imageUrlsCollection->where('is_main', '=', true)->first();

        return PreviewImageData::customFromArrayProductImageUrl($mainImageUrl ?? [])->image_url_sm;
    }

    private static function getPrices(array $product): array
    {
        $regularPrice = $product['product_offer_min']['regular_price'] ?? 0;
        $prices = [
            'price' => self::getMoney($regularPrice),
            'price_old' => null,
        ];
        $discount = $product['product_offer_min']['discount'] ?? 0;
        $promoPrice = $product['product_offer_min']['promo_price'] ?? 0;
        if ($discount > 0 && $promoPrice > 0) {
            $oldPrice = $prices['price'];
            $prices = [
                'price' => self::getMoney($promoPrice),
                'price_old' => $oldPrice
            ];
        }

        return $prices;
    }

    private static function getMoney(int $amount): Money
    {
        return Money::RUB($amount);
    }

    private static function getCategoryIdsByArray(array $product): array
    {
        $categories = collect($product['categories'] ?? []);
        return $categories->pluck('id')->toArray();
    }
}
