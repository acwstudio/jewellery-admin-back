<?php

declare(strict_types=1);

namespace App\Modules\Payment\Models;

use Database\Factories\Modules\Payment\PaymentStatusFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @method static self|null find(int $id)
 * @method static self findOrFail(int $id)
 */
class PaymentStatus extends Model
{
    use HasFactory;

    protected $table = 'payments.payment_statuses';

    protected $fillable = [
        'bank_id',
        'name',
        'full_name',
        'is_active',
    ];


    protected static function newFactory()
    {
        return app(PaymentStatusFactory::class);
    }
}
