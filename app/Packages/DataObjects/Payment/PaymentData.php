<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Payment;

use App\Modules\Payment\Models\Payment;
use Spatie\LaravelData\Data;

class PaymentData extends Data
{
    public function __construct(
        public readonly ?string $bankOrderId
    ) {
    }

    public static function fromModel(Payment $payment): self
    {
        return new self(
            $payment->bank_order_id
        );
    }
}
