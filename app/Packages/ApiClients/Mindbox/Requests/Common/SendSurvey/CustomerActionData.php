<?php

declare(strict_types=1);

namespace App\Packages\ApiClients\Mindbox\Requests\Common\SendSurvey;

use Spatie\LaravelData\Data;

class CustomerActionData extends Data
{
    public function __construct(
        public readonly CustomFields $customFields
    ) {
    }
}
