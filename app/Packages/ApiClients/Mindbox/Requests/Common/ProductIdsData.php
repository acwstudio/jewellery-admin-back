<?php

declare(strict_types=1);

namespace App\Packages\ApiClients\Mindbox\Requests\Common;

use Spatie\LaravelData\Data;

class ProductIdsData extends Data
{
    public function __construct(
        public readonly string $website,
    ) {
    }
}
