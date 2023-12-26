<?php

declare(strict_types=1);

namespace App\Modules\Promotions\Modules\Sales\Models;

use Carbon\Carbon;
use Database\Factories\Modules\Promotions\SaleProductFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $sale_id
 * @property int $product_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property Sale $sale
 *
 * @method static self|null find(int $id)
 * @method static self findOrFail(int $id)
 */
class SaleProduct extends Model
{
    use HasFactory;

    protected $table = 'promotions.sale_products';
    protected $fillable = ['product_id'];

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }

    protected static function newFactory()
    {
        return app(SaleProductFactory::class);
    }
}
