<?php

declare(strict_types=1);

namespace App\Modules\Promotions\Models;

use App\Packages\AttributeCasts\MoneyCast;
use Database\Factories\Modules\Promotions\PromotionBenefitProductFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $promotion_benefit_id
 * @property string $external_id
 * @property string $sku
 * @property string|null $size
 * @property \Money\Money $price
 *
 * @property PromotionBenefit $benefit
 */
class PromotionBenefitProduct extends Model
{
    use HasFactory;

    protected $table = 'promotions.promotion_benefit_products';
    protected $fillable = ['external_id', 'sku', 'size', 'price'];
    protected $casts = [
        'price' => MoneyCast::class
    ];

    public function benefit(): BelongsTo
    {
        return $this->belongsTo(PromotionBenefit::class);
    }

    protected static function newFactory()
    {
        return app(PromotionBenefitProductFactory::class);
    }
}
