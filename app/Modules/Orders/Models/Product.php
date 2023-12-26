<?php

declare(strict_types=1);

namespace App\Modules\Orders\Models;

use App\Packages\AttributeCasts\MoneyCast;
use Database\Factories\Modules\Orders\ProductFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Money\Money;

/**
 * @property int $order_id
 * @property int $product_offer_id
 * @property string $guid
 * @property string $sku
 * @property int $count
 * @property Money $price
 * @property Money|null $discount
 * @property Money $amount
 * @property string|null $size
 */
class Product extends Model
{
    use HasFactory;

    protected $table = 'orders.products';

    protected $fillable = [
        'product_offer_id', 'guid', 'sku', 'count', 'price', 'discount', 'amount', 'size'
    ];

    protected $casts = [
        'price' => MoneyCast::class,
        'discount' => MoneyCast::class,
        'amount' => MoneyCast::class,
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    protected static function newFactory()
    {
        return app(ProductFactory::class);
    }
}
