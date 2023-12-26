<?php

declare(strict_types=1);

namespace App\Packages\ApiClients\Enterprise1C\Response\DeliveryGetCost;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

class DeliveryGetCostResponseData extends Data
{
    public function __construct(
        #[MapInputName('Result')]
        public readonly bool $result,
        #[MapInputName('ErrorMessage')]
        public readonly string $errorMessage,
        public readonly ?DeliveryCostData $data = null
    ) {
    }
}
