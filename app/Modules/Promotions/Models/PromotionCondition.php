<?php

declare(strict_types=1);

namespace App\Modules\Promotions\Models;

use Carbon\Carbon;
use Database\Factories\Modules\Promotions\PromotionConditionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property Carbon $start_at
 * @property Carbon $finish_at
 * @property Collection<PromotionConditionRule> $rules
 * @property Promotion $promotion
 */
class PromotionCondition extends Model
{
    use HasFactory;

    protected $table = 'promotions.promotion_conditions';
    protected $fillable = ['start_at', 'finish_at', 'url_reference', 'promo_agent'];

    protected $casts = [
        'start_at' => 'datetime',
        'finish_at' => 'datetime',
    ];

    public function promotion(): BelongsTo
    {
        return $this->belongsTo(Promotion::class);
    }

    public function rules(): HasMany
    {
        return $this->hasMany(PromotionConditionRule::class);
    }

    protected static function newFactory()
    {
        return app(PromotionConditionFactory::class);
    }
}
