<?php

namespace App\Modules\Stores\Models;

use App\Modules\Stores\Enums\StoreWorkDayEnum;
use Carbon\Carbon;
use Database\Factories\Modules\Store\StoreWorkTimeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @class StoreWorkTime
 * @property int $id
 * @property int $store_id
 * @property string $day
 * @property string $start_time
 * @property string $end_time
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property Store $store
 */
class StoreWorkTime extends Model
{
    use HasFactory;

    protected $table = 'stores.store_work_times';

    protected $fillable = ['day', 'store_id', 'start_time', 'end_time'];

    protected $casts = [
        'day' => StoreWorkDayEnum::class
    ];

    protected static function newFactory()
    {
        return app(StoreWorkTimeFactory::class);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
}
