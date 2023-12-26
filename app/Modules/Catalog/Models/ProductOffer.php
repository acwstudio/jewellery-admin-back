<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Models;

use App\Packages\Enums\Catalog\OfferPriceTypeEnum;
use Database\Factories\Modules\Catalog\ProductOfferFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OpenSearch\ScoutDriverPlus\Searchable;

/**
 * @property int $id
 * @property int $product_id
 * @property string|null $size
 * @property string|null $weight
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property Product $product
 * @property \Illuminate\Support\Collection<ProductOfferPrice> $productOfferPrices
 * @property \Illuminate\Support\Collection<ProductOfferStock> $productOfferStocks
 * @property \Illuminate\Support\Collection<ProductOfferReservation> $productOfferReservations
 *
 * @method static self|null find(int $id)
 * @method static self findOrFail(int $id)
 */
class ProductOffer extends Model
{
    use HasFactory;
    use Searchable;

    protected $table = 'catalog.product_offers';
    protected $with = ['productOfferPrices', 'productOfferStocks'];
    protected $fillable = ['size', 'weight'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function (ProductOffer $model) {
            $exists = ProductOffer::query()
                ->where('product_id', '=', $model->product_id)
                ->where('size', '=', $model->size)
                ->where('weight', '=', $model->weight)
                ->exists();
            if ($exists) {
                throw new \Exception('Торговое предложение с такими параметрами уже существует');
            }
        });

        static::updating(function (ProductOffer $model) {
            $exists = ProductOffer::query()
                ->where('id', '!=', $model->getKey())
                ->where('product_id', '=', $model->product_id)
                ->where('size', '=', $model->size)
                ->where('weight', '=', $model->weight)
                ->exists();
            if ($exists) {
                throw new \Exception('Торговое предложение с такими параметрами уже существует');
            }
        });
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function productOfferPrices(): HasMany
    {
        return $this->hasMany(ProductOfferPrice::class);
    }

    public function productOfferStocks(): HasMany
    {
        return $this->hasMany(ProductOfferStock::class);
    }

    public function productOfferReservations(): HasMany
    {
        return $this->hasMany(ProductOfferReservation::class);
    }

    public function currentProductOfferPrice(): ?ProductOfferPrice
    {
        /** @var ProductOfferPrice|null $productOfferPrice */
        $productOfferPrice = $this->productOfferPrices
            ->where('is_active', '=', true)
            ->sortBy(
                fn (ProductOfferPrice $offerPrice) => match ($offerPrice->type) {
                    OfferPriceTypeEnum::LIVE => 1,
                    OfferPriceTypeEnum::SALE => 2,
                    OfferPriceTypeEnum::PROMO => 3,
                    OfferPriceTypeEnum::REGULAR => 4,
                    default => 10
                }
            )->first();

        return $productOfferPrice;
    }

    public function regularProductOfferPrice(): ?ProductOfferPrice
    {
        /** @var ProductOfferPrice|null $productOfferPrice */
        $productOfferPrice = $this->productOfferPrices
            ->where('is_active', '=', true)
            ->whereIn('type', [OfferPriceTypeEnum::LIVE, OfferPriceTypeEnum::REGULAR])
            ->sortBy(
                fn (ProductOfferPrice $offerPrice) => match ($offerPrice->type) {
                    OfferPriceTypeEnum::LIVE => 1,
                    OfferPriceTypeEnum::REGULAR => 2,
                    default => 10
                }
            )->first();

        return $productOfferPrice;
    }

    public function promoProductOfferPrice(): ?ProductOfferPrice
    {
        /** @var ProductOfferPrice|null $productOfferPrice */
        $productOfferPrice = $this->productOfferPrices
            ->where('is_active', '=', true)
            ->whereIn('type', [OfferPriceTypeEnum::SALE, OfferPriceTypeEnum::PROMO])
            ->sortBy(
                fn (ProductOfferPrice $offerPrice) => match ($offerPrice->type) {
                    OfferPriceTypeEnum::SALE => 1,
                    OfferPriceTypeEnum::PROMO => 2,
                    default => 10
                }
            )->first();

        return $productOfferPrice;
    }

    public function discountProductOfferPrice(): int
    {
        $regular = $this->regularProductOfferPrice();
        if (null === $regular || $regular->type === OfferPriceTypeEnum::LIVE) {
            return 0;
        }

        $promo = $this->promoProductOfferPrice();
        if (null === $promo) {
            return 0;
        }

        $subPrice = $regular->price->subtract($promo->price);

        $regularPrice = (int)$regular->price->getAmount();
        $subPrice = (int)$subPrice->getAmount();
        $discount = $subPrice / $regularPrice * 100;
        return (int)$discount;
    }

    public function currentProductOfferStock(): ?ProductOfferStock
    {
        /** @var ProductOfferStock|null $productOfferStock */
        $productOfferStock = $this->productOfferStocks
            ->where('is_current', '=', true)
            ->first();

        return $productOfferStock;
    }

    public function toSearchableArray()
    {
        $data = $this->toArray();

        $data['regular_price'] = (int)$this->regularProductOfferPrice()?->price->getAmount();
        $data['promo_price'] = (int)$this->promoProductOfferPrice()?->price->getAmount();
        $data['discount'] = $this->discountProductOfferPrice();
        $data['stock'] = (int)$this->currentProductOfferStock()?->count;

        return $data;
    }

    protected static function newFactory()
    {
        return app(ProductOfferFactory::class);
    }
}
