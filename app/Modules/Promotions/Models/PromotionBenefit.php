<?php

declare(strict_types=1);

namespace App\Modules\Promotions\Models;

use App\Modules\Promotions\Enums\PromotionBenefitTypeEnum;
use App\Modules\Promotions\Enums\PromotionBenefitTypeFormEnum;
use App\Modules\Promotions\Modules\Promocodes\Models\PromocodePrice;
use App\Modules\Promotions\Modules\Promocodes\Models\PromocodeUsage;
use App\Packages\AttributeCasts\MoneyCast;
use Database\Factories\Modules\Promotions\PromotionBenefitFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Money\Money;

/**
 * @property int $id
 * @property PromotionBenefitTypeEnum $type
 * @property PromotionBenefitTypeFormEnum|null $type_form
 * @property string $promocode
 * @property Money|null $nominal_amount
 * @property int|null $percent_amount
 * @property Money|null $max_nominal_amount
 * @property int|null $use_count
 * @property bool|null $is_free_delivery
 * @property bool|null $is_gift
 * @property bool|null $is_gift_from_shop_cart
 * @property int|null $gift_from_shop_cart_count
 *
 * @property Promotion $promotion
 * @property Collection<PromotionBenefitGift> $gifts
 * @property Collection<PromotionBenefitProduct> $products
 */
class PromotionBenefit extends Model
{
    use HasFactory;

    protected $table = 'promotions.promotion_benefits';

    protected $fillable = [
        'type', 'type_form', 'promocode', 'nominal_amount', 'percent_amount', 'max_nominal_amount',
        'use_count', 'is_free_delivery', 'is_gift', 'is_gift_from_shop_cart', 'gift_from_shop_cart_count'
    ];

    protected $casts = [
        'type' => PromotionBenefitTypeEnum::class,
        'type_form' => PromotionBenefitTypeFormEnum::class,
        'nominal_amount' => MoneyCast::class,
        'max_nominal_amount' => MoneyCast::class,
    ];

    public function gifts(): HasMany
    {
        return $this->hasMany(PromotionBenefitGift::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(PromotionBenefitProduct::class);
    }

    public function promotion(): BelongsTo
    {
        return $this->belongsTo(Promotion::class);
    }

    public function promocodeUsages(): HasMany
    {
        return $this->hasMany(PromocodeUsage::class);
    }

    public function promocodePrice(): HasMany
    {
        return $this->hasMany(PromocodePrice::class);
    }

    protected static function newFactory()
    {
        return app(PromotionBenefitFactory::class);
    }
}
