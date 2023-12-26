<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Payment\Messages;

use App\Packages\DataTransformers\MoneyTransformer;
use Money\Money;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;

class PaymentStatusMessageData extends Data
{
    public function __construct(
        public readonly string $order_number,
        public readonly string $payment_date,
        public readonly string $transaction,
        public readonly string $cashier_name,
        #[WithTransformer(MoneyTransformer::class)]
        public readonly Money $payment_amount,
        public readonly int $payment_type
    ) {
    }
}
