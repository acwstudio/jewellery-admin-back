<?php

declare(strict_types=1);

namespace App\Modules\Payment\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $full_name
 * @property bool $is_active
 */
class PaymentSystem extends Model
{
    protected $table = 'payments.payment_systems';
}
