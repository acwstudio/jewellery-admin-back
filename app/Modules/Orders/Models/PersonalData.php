<?php

declare(strict_types=1);

namespace App\Modules\Orders\Models;

use App\Packages\AttributeCasts\PhoneNumberCast;
use App\Packages\Support\PhoneNumber;
use Database\Factories\Modules\Orders\PersonalDataFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $order_id
 * @property PhoneNumber $phone
 * @property string $email
 * @property string $name
 * @property string $surname
 * @property string|null $patronymic
 */
class PersonalData extends Model
{
    use HasFactory;

    protected $table = 'orders.personal_data';
    protected $fillable = ['phone', 'email', 'name', 'surname', 'patronymic'];

    protected $casts = [
        'phone' => PhoneNumberCast::class,
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    protected static function newFactory()
    {
        return app(PersonalDataFactory::class);
    }
}
