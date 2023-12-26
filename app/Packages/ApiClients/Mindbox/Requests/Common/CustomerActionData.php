<?php

declare(strict_types=1);

namespace App\Packages\ApiClients\Mindbox\Requests\Common;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

class CustomerActionData extends Data
{
    public function __construct(
        public readonly Collection $customFields
    ) {
    }
}
