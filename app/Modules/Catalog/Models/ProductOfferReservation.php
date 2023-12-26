<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Models;

use App\Packages\Enums\Catalog\OfferReservationStatusEnum;
use Database\Factories\Modules\Catalog\ProductOfferReservationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OpenSearch\ScoutDriverPlus\Searchable;

/**
 * @property int $id
 * @property int $product_offer_id
 * @property int $count
 * @property OfferReservationStatusEnum $status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @method static self|null find(int $id)
 * @method static self findOrFail(int $id)
 */
class ProductOfferReservation extends Model
{
    use HasFactory;
    use Searchable;

    protected $table = 'catalog.product_offer_reservations';

    protected $fillable = [
        'count', 'status'
    ];

    protected $casts = [
        'status' => OfferReservationStatusEnum::class
    ];

    public function productOffer(): BelongsTo
    {
        return $this->belongsTo(ProductOffer::class);
    }

    protected static function newFactory()
    {
        return app(ProductOfferReservationFactory::class);
    }
}
