<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Models;

use App\Modules\Catalog\Traits\ProductTrait;
use App\Packages\Enums\LiquidityEnum;
use Database\Factories\Modules\Catalog\ProductFactory;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OpenSearch\ScoutDriverPlus\Searchable;

/**
 * @property int $id
 * @property string|null $external_id
 * @property string $sku
 * @property string $name
 * @property string $summary
 * @property string $description
 * @property string|null $catalog_number
 * @property string|null $supplier
 * @property LiquidityEnum|null $liquidity
 * @property float|null $stamp
 * @property string $manufacture_country
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string|null $meta_keywords
 * @property bool $is_active
 * @property bool|null $is_drop_shipping
 * @property int $rank
 * @property int|null $popularity
 * @property \App\Modules\Catalog\Models\PreviewImage|null $previewImage
 * @property Brand|null $brand
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string|null $name_1c
 * @property string|null $description_1c
 * @property string $slug
 *
 * @property \Illuminate\Support\Collection<Category> $categories
 * @property \Illuminate\Support\Collection<ProductOffer> $productOffers
 * @property \Illuminate\Support\Collection<ProductFeature> $productFeatures
 * @property \Illuminate\Support\Collection<ProductImageUrl> $imageUrls
 * @property \Illuminate\Support\Collection<PreviewImage> $images
 * @property \Illuminate\Support\Collection<ProductVideoUrl> $videoUrls
 *
 * @method static self|null find(int $id)
 * @method static self findOrFail(int $id)
 */
class Product extends Model
{
    use HasFactory;
    use Searchable;
    use ProductTrait;

    protected $table = 'catalog.products';
    protected $with = [
        'brand', 'categories', 'previewImage', 'productOffers', 'imageUrls', 'images', 'productFeatures', 'videoUrls'
    ];
    protected $fillable = [
        'sku', 'name', 'summary', 'description', 'catalog_number',
        'supplier', 'liquidity', 'stamp', 'manufacture_country',
        'meta_title', 'meta_description', 'meta_keywords',
        'is_active', 'is_drop_shipping', 'rank', 'popularity', 'external_id',
        'name_1c', 'description_1c', 'slug'
    ];

    protected $casts = [
        'liquidity' => LiquidityEnum::class,
    ];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(
            Category::class,
            'catalog.product_categories',
            'product_id',
            'category_id'
        )->withTimestamps();
    }

    public function previewImage(): BelongsTo
    {
        return $this->belongsTo(PreviewImage::class);
    }

    public function productOffers(): HasMany
    {
        return $this->hasMany(ProductOffer::class);
    }

    public function imageUrls(): HasMany
    {
        return $this->hasMany(ProductImageUrl::class);
    }

    public function images(): BelongsToMany
    {
        return $this->belongsToMany(
            PreviewImage::class,
            'catalog.product_images',
            'product_id',
            'preview_image_id'
        )->orderByPivot('order_column');
    }

    public function productFeatures(): HasMany
    {
        return $this->hasMany(ProductFeature::class);
    }

    public function videoUrls(): HasMany
    {
        return $this->hasMany(ProductVideoUrl::class);
    }

    protected function makeAllSearchableUsing(Builder $query): Builder
    {
        return $query->with($this->with);
    }

    public function toSearchableArray()
    {
        $data = $this->toArray();

        $this->setPriceMin($data);
        $this->setDiscountMax($data);
        $this->setProductOfferItems($data);
        $this->setProductFeatures($data);

        return $data;
    }

    protected static function newFactory()
    {
        return app(ProductFactory::class);
    }

    private function setPriceMin(array &$data): void
    {
        /** @var ProductOffer|null $productOffer */
        $productOffer = $this->productOffers
            ->where('productOfferPrices.*.is_active', '=', true)
            ->sortBy(
                fn (ProductOffer $productOffer) => $productOffer->currentProductOfferPrice()?->price->getAmount()
            )->first();

        $data['price_min'] = (int)$productOffer?->currentProductOfferPrice()?->price->getAmount();

        if (null !== $productOffer) {
            $this->setProductOfferMin($productOffer, $data);
        }
    }

    private function setDiscountMax(array &$data): void
    {
        /** @var ProductOffer|null $productOffer */
        $productOffer = $this->productOffers->sortBy(
            callback: fn (ProductOffer $productOffer) => $productOffer->discountProductOfferPrice(),
            descending: true
        )->first();

        $data['discount_max'] = $productOffer?->discountProductOfferPrice() ?? 0;
    }

    private function setProductOfferMin(ProductOffer $productOffer, array &$data): void
    {
        $data['product_offer_min'] = [
            'product_offer_id' => $productOffer->id,
            'regular_price' => (int)$productOffer->regularProductOfferPrice()?->price->getAmount(),
            'promo_price' => (int)$productOffer->promoProductOfferPrice()?->price->getAmount(),
            'discount' => $productOffer->discountProductOfferPrice()
        ];
    }

    private function setProductOfferItems(array &$data): void
    {
        $productOffers = $this->productOffers
            ->where('productOfferPrices.*.is_active', '=', true);
        $items = [];
        /** @var ProductOffer $productOffer */
        foreach ($productOffers as $productOffer) {
            $productOfferData = $productOffer->toSearchableArray();
            unset($productOfferData['product_offer_prices']);
            unset($productOfferData['product_offer_stocks']);
            $items[] = $productOfferData;
        }

        $data['product_offer_items'] = $items;
    }

    private function setProductFeatures(array &$data): void
    {
        $items = [];
        /** @var ProductFeature $productFeature */
        foreach ($this->productFeatures as $productFeature) {
            $items[] = $productFeature->feature->value;
        }

        $data['feature_all'] = implode('|', $items);
    }
}
