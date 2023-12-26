<?php

declare(strict_types=1);

namespace App\Modules\Promotions\Models;

use App\Modules\Promotions\Enums\PromotionConditionTypeEnum;
use App\Packages\AttributeCasts\MoneyCast;
use App\Packages\Enums\OperatorEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Money\Money;

/**
 * @property PromotionConditionTypeEnum $type
 * @property Money $total_amount
 * @property Collection<PromotionConditionRulePhone> $phones
 * @property PromotionCondition $condition
 */
class PromotionConditionRule extends Model
{
    use HasFactory;

    protected $table = 'promotions.promotion_condition_rules';
    protected $fillable = ['type', 'total_amount', 'total_count', 'operator', 'feature_name', 'feature_value'];

    protected $casts = [
        'type' => PromotionConditionTypeEnum::class,
        'total_amount' => MoneyCast::class,
        'operator' => OperatorEnum::class,
    ];

    public function condition(): BelongsTo
    {
        return $this->belongsTo(PromotionCondition::class);
    }

    public function phones(): HasMany
    {
        return $this->hasMany(PromotionConditionRulePhone::class);
    }
}
