<?php

declare(strict_types=1);

namespace App\Modules\Promotions\Modules\Promocodes\Models;

use App\Modules\Promotions\Models\PromotionBenefit;
use App\Packages\AttributeCasts\MoneyCast;
use Database\Factories\Modules\Promotions\PromocodePriceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $product_offer_id
 * @property string $shop_cart_token
 * @property \Money\Money $price
 * @property int $promotion_benefit_id
 *
 * @property PromotionBenefit $promotionBenefit
 */
class PromocodePrice extends Model
{
    use HasFactory;

    protected $table = 'promotions.promocode_prices';
    protected $fillable = ['product_offer_id', 'shop_cart_token', 'price'];

    protected $casts = [
        'price' => MoneyCast::class,
    ];

    public function promotionBenefit(): BelongsTo
    {
        return $this->belongsTo(PromotionBenefit::class);
    }

    protected static function newFactory()
    {
        return app(PromocodePriceFactory::class);
    }
}
