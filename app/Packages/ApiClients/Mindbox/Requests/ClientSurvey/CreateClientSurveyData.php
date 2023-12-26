<?php

declare(strict_types=1);

namespace App\Packages\ApiClients\Mindbox\Requests\ClientSurvey;

use App\Packages\ApiClients\Mindbox\Requests\Common\CustomerActionData;
use App\Packages\ApiClients\Mindbox\Requests\Common\CustomerData;
use Spatie\LaravelData\Data;

class CreateClientSurveyData extends Data
{
    public function __construct(
        public readonly CustomerActionData $customerAction,
        public readonly CustomerData $customer,
        public readonly string $executionDateTimeUtc
    ) {
    }
}
