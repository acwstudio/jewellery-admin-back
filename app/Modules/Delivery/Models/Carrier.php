<?php

declare(strict_types=1);

namespace App\Modules\Delivery\Models;

use Database\Factories\Modules\Delivery\CarrierFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $external_id
 * @property string $name
 */
class Carrier extends Model
{
    use HasFactory;

    protected $table = 'delivery.carriers';
    protected $fillable = ['name', 'external_id'];

    public function pvz(): HasMany
    {
        return $this->hasMany(Pvz::class);
    }

    protected static function newFactory()
    {
        return app(CarrierFactory::class);
    }
}
