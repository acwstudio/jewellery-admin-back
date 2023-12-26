<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Models;

use App\Packages\AttributeCasts\MoneyCast;
use App\Packages\Enums\Catalog\OfferPriceTypeEnum;
use Database\Factories\Modules\Catalog\ProductOfferPriceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OpenSearch\ScoutDriverPlus\Searchable;

/**
 * @property int $id
 * @property int $product_offer_id
 * @property \Money\Money $price
 * @property OfferPriceTypeEnum $type
 * @property bool $is_active
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property \App\Modules\Catalog\Models\ProductOffer $productOffer
 */
class ProductOfferPrice extends Model
{
    use HasFactory;
    use Searchable;

    protected $table = 'catalog.product_offer_prices';

    protected $fillable = [
        'price', 'type', 'is_active'
    ];

    protected $casts = [
        'price' => MoneyCast::class,
        'type' => OfferPriceTypeEnum::class
    ];

    public function productOffer(): BelongsTo
    {
        return $this->belongsTo(ProductOffer::class);
    }

    protected static function newFactory()
    {
        return app(ProductOfferPriceFactory::class);
    }

    public function toSearchableArray()
    {
        $data = $this->toArray();
        $data['price'] = (int)$this->price->getAmount();

        return $data;
    }
}
