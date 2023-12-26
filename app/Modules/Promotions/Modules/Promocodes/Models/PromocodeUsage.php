<?php

declare(strict_types=1);

namespace App\Modules\Promotions\Modules\Promocodes\Models;

use App\Modules\Promotions\Models\PromotionBenefit;
use Database\Factories\Modules\Promotions\PromocodeUsageFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $shop_cart_token
 * @property string $user_id
 * @property int|null $order_id
 * @property bool $is_active
 * @property int $promotion_benefit_id
 *
 * @property PromotionBenefit $promotionBenefit
 *
 * @method static self|null find(int $id)
 * @method static self findOrFail(int $id)
 */
class PromocodeUsage extends Model
{
    use HasFactory;

    protected $table = 'promotions.promocode_usages';
    protected $fillable = ['shop_cart_token', 'user_id', 'order_id', 'is_active'];

    public function promotionBenefit(): BelongsTo
    {
        return $this->belongsTo(PromotionBenefit::class);
    }

    protected static function newFactory()
    {
        return app(PromocodeUsageFactory::class);
    }
}
