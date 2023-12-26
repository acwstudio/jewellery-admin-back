<?php

declare(strict_types=1);

namespace App\Modules\Payment\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
*/
class PaymentOperationType extends Model
{
    protected $table = 'payments.payment_operation_types';
}
