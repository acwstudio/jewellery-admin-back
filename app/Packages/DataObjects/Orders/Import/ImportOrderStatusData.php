<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Orders\Import;

use App\Packages\Enums\Orders\OrderStatusEnum;
use Carbon\Carbon;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;

class ImportOrderStatusData extends Data
{
    public function __construct(
        public readonly int $order_id,
        #[MapInputName('UID')]
        public readonly string $external_id,
        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d\TH:i:s')]
        public readonly Carbon $date_time,
        public readonly OrderStatusEnum $status
    ) {
    }
}
