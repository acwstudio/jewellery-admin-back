<?php

declare(strict_types=1);

namespace App\Modules\Promotions\Modules\Sales\Models;

use App\Modules\Promotions\Models\Promotion;
use Carbon\Carbon;
use Database\Factories\Modules\Promotions\SaleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $promotion_id
 * @property string $title
 * @property string $slug
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property Promotion $promotion
 * @property \Illuminate\Support\Collection<SaleProduct> $products
 *
 * @method static self|null find(int $id)
 * @method static self findOrFail(int $id)
 */
class Sale extends Model
{
    use HasFactory;

    protected $table = 'promotions.sales';
    protected $fillable = ['title', 'slug'];

    public function promotion(): BelongsTo
    {
        return $this->belongsTo(Promotion::class, 'promotion_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(SaleProduct::class, 'sale_id');
    }

    protected static function newFactory()
    {
        return app(SaleFactory::class);
    }
}
