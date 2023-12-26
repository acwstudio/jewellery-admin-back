<?php

declare(strict_types=1);

namespace App\Modules\Promotions\Models;

use App\Packages\AttributeCasts\PhoneNumberCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PromotionConditionRulePhone extends Model
{
    use HasFactory;

    protected $table = 'promotions.promotion_condition_rule_phones';
    protected $fillable = ['phone'];

    protected $casts = [
        'phone' => PhoneNumberCast::class,
    ];

    public function rule(): BelongsTo
    {
        return $this->belongsTo(PromotionConditionRule::class);
    }
}
