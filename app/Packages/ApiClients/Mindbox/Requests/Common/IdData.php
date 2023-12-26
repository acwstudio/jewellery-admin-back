<?php

declare(strict_types=1);

namespace App\Packages\ApiClients\Mindbox\Requests\Common;

use Spatie\LaravelData\Data;

class IdData extends Data
{
    public function __construct(
        public readonly ?string $mindboxId = null,
        public readonly ?string $websiteID = null,
        public readonly ?string $crmID = null
    ) {
    }
}
