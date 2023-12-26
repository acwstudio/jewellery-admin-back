<?php

declare(strict_types=1);

namespace App\Modules\Stores\Models;

use Carbon\Carbon;
use Database\Factories\Modules\Store\StoreFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @class Store
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $address
 * @property numeric $latitude
 * @property numeric $longitude
 * @property string $phone
 * @property boolean $isWorkWeekdays
 * @property boolean $isWorkSaturday
 * @property boolean $isWorkSunday
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @method static self find(int $id)
 */
class Store extends Model
{
    use HasFactory;

    protected $table = 'stores.stores';
    protected $fillable = ['name', 'description', 'address', 'phone', 'latitude', 'longitude', 'isWorkWeekdays',
        'isWorkSaturday', 'isWorkSunday'];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float'
    ];

    protected static function newFactory()
    {
        return app(StoreFactory::class);
    }

    public function subways(): BelongsToMany
    {
        return $this->belongsToMany(Subway::class, 'stores.store_subways_stores')
            ->withPivot('distance');
    }

    public function types(): BelongsToMany
    {
        return $this->belongsToMany(StoreType::class, 'stores.store_types_stores');
    }

    public function workTimes(): HasMany
    {
        return $this->hasMany(StoreWorkTime::class);
    }
}
