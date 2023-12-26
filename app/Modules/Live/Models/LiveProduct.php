<?php

declare(strict_types=1);

namespace App\Modules\Live\Models;

use App\Modules\Catalog\Models\Product;
use Database\Factories\Modules\Live\LiveProductFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property int $product_id
 * @property int $number
 * @property bool $on_live
 * @property \Carbon\Carbon $started_at
 * @property \Carbon\Carbon $expired_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property Product $product
 *
 * @method static self|null find(int $id)
 * @method static self findOrFail(int $id)
 */
class LiveProduct extends Model
{
    use HasFactory;

    protected $table = 'live.live_products';

    protected $fillable = ['product_id', 'number', 'on_live', 'started_at', 'expired_at'];

    protected $casts = [
        'started_at' => 'datetime',
        'expired_at' => 'datetime'
    ];

    public function product(): HasOne
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    protected static function newFactory()
    {
        return app(LiveProductFactory::class);
    }
}
