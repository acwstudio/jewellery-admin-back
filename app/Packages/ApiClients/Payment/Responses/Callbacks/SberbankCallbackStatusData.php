<?php

declare(strict_types=1);

namespace App\Packages\ApiClients\Payment\Responses\Callbacks;

use App\Packages\ApiClients\Payment\Enums\OperationEnum;
use Spatie\LaravelData\Data;

class SberbankCallbackStatusData extends Data
{
    public function __construct(
        public readonly string $mdOrder,
        public readonly string $orderNumber,
        public readonly OperationEnum $operation,
        public readonly bool $status,
        public readonly ?string $checksum,
    ) {
    }
}
