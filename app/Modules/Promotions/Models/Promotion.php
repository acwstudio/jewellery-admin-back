<?php

declare(strict_types=1);

namespace App\Modules\Promotions\Models;

use Database\Factories\Modules\Promotions\PromotionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property string $external_id
 * @property string|null $description
 * @property bool $is_active
 *
 * @property PromotionCondition $condition
 * @property Collection<PromotionBenefit> $benefits
 *
 * @method static self|null find(int $id)
 * @method static self findOrFail(int $id)
 * @method static create(array $attributes = [])
 */
class Promotion extends Model
{
    use HasFactory;

    protected $table = 'promotions.promotions';
    protected $fillable = ['external_id', 'description', 'is_active'];

    public function benefits(): HasMany
    {
        return $this->hasMany(PromotionBenefit::class);
    }

    public function condition(): HasOne
    {
        return $this->hasOne(PromotionCondition::class);
    }

    protected static function newFactory()
    {
        return app(PromotionFactory::class);
    }
}
