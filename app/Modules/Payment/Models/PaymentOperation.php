<?php

declare(strict_types=1);

namespace App\Modules\Payment\Models;

use App\Modules\Payment\Traits\HasConfig;
use App\Modules\Users\Models\User;
use Database\Factories\Modules\Payment\PaymentOperationFactory;
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int                       $id
 * @property Payment|null              $payment
 * @property User|null                 $user
 * @property PaymentOperationType|null $type
 * @method static Factory create(array $attributes = [])
 */
class PaymentOperation extends Model
{
    use HasFactory;
    use HasConfig;

    protected $table = 'payments.payment_operations';

    protected $casts = [
        'request_json'  => 'array',
        'response_json' => 'array',
    ];

    protected $fillable = [
        'payment_id',
        'user_id',
        'type_id',
        'request_json',
        'response_json',
    ];

    /**
     * Пользователь-инициатор операции
     *
     * @return BelongsTo|null
     * @throws Exception
     */
    public function user(): ?BelongsTo
    {
        return $this->belongsTo(
            $this->getConfigParam('user.model'),
            'user_id',
            $this->getConfigParam('user.primary_key'),
        );
    }

    /**
     * Платеж
     *
     * @return BelongsTo
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'payment_id', 'id');
    }

    /**
     * Тип операции
     *
     * @return BelongsTo
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(PaymentOperationType::class, 'type_id', 'id');
    }

    protected static function newFactory()
    {
        return app(PaymentOperationFactory::class);
    }
}
