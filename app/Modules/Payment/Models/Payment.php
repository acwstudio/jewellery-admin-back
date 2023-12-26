<?php

declare(strict_types=1);

namespace App\Modules\Payment\Models;

use App\Modules\Orders\Models\Order;
use App\Modules\Payment\Enums\PaymentStatusEnum;
use App\Modules\Payment\Enums\PaymentSystemTypeEnum;
use Database\Factories\Modules\Payment\PaymentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int $id
 * @property string|null $bank_order_id
 * @property int $status_id
 * @property int $system_id
 * @property string $payment_type
 * @property int $payment_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property-read PaymentOperation[]|null $operations
 * @property-read PaymentSystem|null $system
 * @property-read PaymentStatus|null $status
 * @property-read Order|null $order
 * @property-read SberbankPayment|SamsungPayPayment|ApplePayPayment|GooglePayPayment|null $payment
 *
 * @method static self|null find(int $id)
 * @method static self findOrFail(int $id)
 * @method static self destroy(array $ids)
 */
class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments.payments';

    protected $fillable = [
        'bank_order_id',
        'status_id',
        'system_id',
        'payment_type',
        'payment_id',
    ];

    protected $casts = [
        'status_id' => PaymentStatusEnum::class,
        'system_id' => PaymentSystemTypeEnum::class,
    ];

    /**
     * Операции по платежу
     *
     * @return HasMany
     */
    public function operations(): HasMany
    {
        return $this->hasMany(PaymentOperation::class, 'payment_id', 'id');
    }

    /**
     * Платежная система
     *
     * @return BelongsTo
     */
    public function system(): BelongsTo
    {
        return $this->belongsTo(PaymentSystem::class, 'system_id', 'id');
    }

    /**
     * Статус платежа
     *
     * @return BelongsTo
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(PaymentStatus::class, 'status_id', 'id');
    }

    public function order(): HasOne
    {
        return $this->hasOne(Order::class, 'payment_id', 'id');
    }

    /**
     * Модель платежа
     *
     * @return MorphTo
     */
    public function payment(): MorphTo
    {
        return $this->morphTo();
    }

    protected static function newFactory()
    {
        return app(PaymentFactory::class);
    }
}
