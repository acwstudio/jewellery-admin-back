<?php

declare(strict_types=1);

namespace App\Modules\Promotions\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $external_id
 * @property string $size
 * @property int $count
 */
class PromotionBenefitGift extends Model
{
    use HasFactory;

    protected $table = 'promotions.promotion_benefit_gifts';
    protected $fillable = ['external_id', 'size', 'count'];

    public function benefit(): BelongsTo
    {
        return $this->belongsTo(PromotionBenefit::class);
    }
}
