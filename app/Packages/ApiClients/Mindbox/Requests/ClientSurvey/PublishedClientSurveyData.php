<?php

declare(strict_types=1);

namespace App\Packages\ApiClients\Mindbox\Requests\ClientSurvey;

use App\Packages\ApiClients\Mindbox\Requests\Common\SendSurvey\CustomerData;
use App\Packages\ApiClients\Mindbox\Requests\Common\SendSurvey\CustomerActionData;
use Spatie\LaravelData\Data;

class PublishedClientSurveyData extends Data
{
    public function __construct(
        public readonly CustomerActionData $customerAction,
        public readonly CustomerData $customer,
    ) {
    }
}
