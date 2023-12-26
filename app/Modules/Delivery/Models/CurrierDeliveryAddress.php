<?php

declare(strict_types=1);

namespace App\Modules\Delivery\Models;

use App\Modules\Users\Models\User;
use Database\Factories\Modules\Delivery\CurrierDeliveryAddressFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property User $user
 * @property string $address
 * @property int $zip_code
 * @property string $region
 * @property string|null $settlement
 * @property string $city
 * @property string $street
 * @property string $house
 * @property string|null $flat
 * @property string|null $block
 * @property string $fias_region_id
 * @property string $fias_street_id
 * @property string $fias_house_id
 * @property string $client_address
 */
class CurrierDeliveryAddress extends Model
{
    use HasFactory;

    protected $table = 'delivery.currier_delivery_addresses';

    protected $fillable = [
        'address', 'zip_code', 'region', 'settlement', 'city', 'street', 'house', 'flat',
        'block', 'fias_region_id', 'fias_street_id', 'fias_house_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, ownerKey: 'user_id');
    }

    public function deliveries(): HasMany
    {
        return $this->hasMany(CurrierDelivery::class, 'currier_delivery_address_id');
    }

    protected static function newFactory()
    {
        return app(CurrierDeliveryAddressFactory::class);
    }

    public function getClientAddressAttribute(): string
    {
        $address = "г. $this->city, $this->street, д. $this->house";

        if (isset($this->flat)) {
            $address .= ", кв. $this->flat";
        }

        if (isset($this->block)) {
            $address .= ", $this->block";
        }

        return $address;
    }
}
