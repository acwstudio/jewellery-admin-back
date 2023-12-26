<?php

declare(strict_types=1);

namespace App\Packages\ApiClients\Mindbox\Requests\Common\SendSurvey;

use Spatie\LaravelData\Data;

class CustomFields extends Data
{
    public function __construct(
        public readonly int $rateStore,
        public readonly array $whatImprove,
        public readonly string $shop
    ) {
    }
}
