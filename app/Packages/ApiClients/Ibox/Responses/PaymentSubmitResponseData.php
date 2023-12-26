<?php

declare(strict_types=1);

namespace App\Packages\ApiClients\Ibox\Responses;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

class PaymentSubmitResponseData extends Data
{
    public function __construct(
        #[MapInputName('Transaction')]
        public readonly PaymentSubmitTransactionResponsePartData $transaction
    ) {
    }
}
