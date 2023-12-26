<?php

declare(strict_types=1);

namespace App\Modules\Live\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $product_id
 * @property int $number
 * @property bool $on_live
 * @property \Carbon\Carbon $started_at
 * @property \Carbon\Carbon $expired_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @method static self|null find(int $id)
 * @method static self findOrFail(int $id)
 */
class LiveProductListItem extends Model
{
    use HasFactory;

    protected $table = 'live.live_products';

    protected $casts = [
        'started_at' => 'datetime',
        'expired_at' => 'datetime'
    ];
}
