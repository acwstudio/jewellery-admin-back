<?php

declare(strict_types=1);

namespace App\Modules\Orders\Models;

use App\Modules\Payment\Enums\PaymentTypeEnum;
use App\Modules\Payment\Models\Payment;
use App\Modules\Users\Models\User;
use App\Packages\AttributeCasts\MoneyCast;
use App\Packages\Enums\Orders\OrderStatusEnum;
use Carbon\Carbon;
use Database\Factories\Modules\Orders\OrderFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;
use Money\Money;

/**
 * @property int $id
 * @property string $project
 * @property string $country
 * @property string $currency
 * @property string|null $comment
 * @property Delivery $delivery
 * @property Money $summary
 * @property PaymentTypeEnum $payment_type
 * @property-read string $payment_url
 * @property Carbon $created_at
 * @property string|null $promotion_external_id
 * @property string|null $shop_cart_token
 * @property int|null $payment_id
 * @property string $user_id
 * @property OrderStatusEnum $status
 * @property Carbon $status_date
 * @property string|null $external_id
 *
 * @property-read Payment|null $payment
 * @property PersonalData $personalData
 * @property Collection<Product> $products
 *
 * @method static self|null find(int $id)
 * @method static self findOrFail(int $id)
 */
class Order extends Model
{
    use HasFactory;

    protected $table = 'orders.orders';
    protected $with = ['payment', 'delivery', 'products'];

    protected $fillable = [
        'project', 'country', 'currency', 'comment', 'summary', 'promotion_external_id', 'shop_cart_token',
        'payment_type', 'payment_id', 'status', 'status_date', 'external_id'
    ];

    protected $casts = [
        'summary' => MoneyCast::class,
        'payment_type' => PaymentTypeEnum::class,
        'status' => OrderStatusEnum::class,
        'status_date' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, ownerKey: 'user_id');
    }

    public function personalData(): HasOne
    {
        return $this->hasOne(PersonalData::class);
    }

    public function delivery(): HasOne
    {
        return $this->hasOne(Delivery::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    public function getPaymentUrlAttribute(): ?string
    {
        if (null === $this->payment->payment) {
            return null;
        }

        return $this->payment->payment->bank_form_url;
    }

    protected static function newFactory()
    {
        return app(OrderFactory::class);
    }
}
