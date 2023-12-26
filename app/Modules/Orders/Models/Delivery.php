<?php

declare(strict_types=1);

namespace App\Modules\Orders\Models;

use App\Packages\AttributeCasts\MoneyCast;
use App\Packages\Enums\Orders\DeliveryType;
use Database\Factories\Modules\Orders\DeliveryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Money\Money;

/**
 * @property int $order_id
 * @property Money $price
 * @property DeliveryType $delivery_type
 * @property int|null $pvz_id
 * @property string|null $currier_delivery_id
 */
class Delivery extends Model
{
    use HasFactory;

    protected $table = 'orders.deliveries';
    protected $fillable = ['price', 'delivery_type', 'pvz_id', 'currier_delivery_id'];

    protected $casts = [
        'delivery_type' => DeliveryType::class,
        'price' => MoneyCast::class,
    ];

    protected static function newFactory()
    {
        return app(DeliveryFactory::class);
    }
}
