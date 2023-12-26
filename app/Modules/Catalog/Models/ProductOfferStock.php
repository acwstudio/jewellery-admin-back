<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Models;

use App\Packages\Enums\Catalog\OfferStockReasonEnum;
use Database\Factories\Modules\Catalog\ProductOfferStockFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OpenSearch\ScoutDriverPlus\Searchable;

/**
 * @property int $id
 * @property int $product_offer_id
 * @property int $count
 * @property bool $is_current
 * @property OfferStockReasonEnum $reason
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property ProductOffer $productOffer
 *
 * @method static self|null find(int $id)
 * @method static self findOrFail(int $id)
 */
class ProductOfferStock extends Model
{
    use HasFactory;
    use Searchable;

    protected $table = 'catalog.product_offer_stocks';

    protected $fillable = [
        'count', 'is_current', 'reason'
    ];

    protected $casts = [
        'reason' => OfferStockReasonEnum::class
    ];

    public function productOffer(): BelongsTo
    {
        return $this->belongsTo(ProductOffer::class);
    }

    protected static function newFactory()
    {
        return app(ProductOfferStockFactory::class);
    }
}
