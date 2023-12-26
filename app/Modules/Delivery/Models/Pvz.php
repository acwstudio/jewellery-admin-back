<?php

declare(strict_types=1);

namespace App\Modules\Delivery\Models;

use App\Modules\Users\Models\User;
use App\Packages\AttributeCasts\MoneyCast;
use Database\Factories\Modules\Delivery\PvzFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Money\Money;

/**
 * @property int $id
 * @property string $external_id
 * @property string $latitude
 * @property string $longitude
 * @property string $work_time
 * @property string $area
 * @property string $city
 * @property string $district
 * @property string $street
 * @property Money $price
 * @property string $address
 * @property Carrier $carrier
 * @property \Illuminate\Support\Collection $metro
 */
class Pvz extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'delivery.pvz';

    protected $fillable = [
        'latitude', 'longitude', 'external_id', 'work_time', 'area', 'city', 'district', 'street', 'price', 'address',
    ];

    protected $casts = [
        'price' => MoneyCast::class,
    ];

    public function carrier(): BelongsTo
    {
        return $this->belongsTo(Carrier::class);
    }

    public function user(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'delivery.user_pvz', 'pvz_id', 'user_id');
    }

    public function metro(): BelongsToMany
    {
        return $this->belongsToMany(Metro::class, 'delivery.metro_pvz', 'pvz_id', 'metro_id');
    }

    protected static function newFactory()
    {
        return app(PvzFactory::class);
    }
}
