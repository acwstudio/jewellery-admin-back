<?php

declare(strict_types=1);

namespace App\Packages\ApiClients\Mindbox\Requests\Common\SendSurvey;

use Spatie\LaravelData\Data;

class CustomerData extends Data
{
    public function __construct(
        public readonly ?string $mobilePhone = null,
    ) {
    }
}
