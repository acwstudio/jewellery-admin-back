<?php

declare(strict_types=1);

namespace App\Modules\Delivery\Models;

use App\Packages\AttributeCasts\MoneyCast;
use App\Packages\Traits\HasUUID;
use Database\Factories\Modules\Delivery\CurrierDeliveryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Money\Money;

/**
 * @property string $id
 * @property string $carrier_id
 * @property Money $price
 * @property CurrierDeliveryAddress $address
 */
class CurrierDelivery extends Model
{
    use HasFactory;
    use HasUUID;

    protected $table = 'delivery.currier_deliveries';
    protected $fillable = ['carrier_id', 'price'];

    protected $casts = [
        'price' => MoneyCast::class,
    ];

    public function address(): BelongsTo
    {
        return $this->belongsTo(CurrierDeliveryAddress::class, 'currier_delivery_address_id');
    }

    protected static function newFactory()
    {
        return app(CurrierDeliveryFactory::class);
    }
}
