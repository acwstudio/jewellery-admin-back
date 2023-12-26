<?php

declare(strict_types=1);

namespace App\Packages\ApiClients\Ibox\Responses;

use Carbon\Carbon;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;

class PaymentSubmitTransactionResponsePartData extends Data
{
    public function __construct(
        #[MapInputName('ID')]
        public readonly string $id,
        #[MapInputName('Date')]
        #[WithCast(DateTimeInterfaceCast::class, 'Y-m-d\TH:i:s.v')]
        public readonly Carbon $date,
    ) {
    }
}
