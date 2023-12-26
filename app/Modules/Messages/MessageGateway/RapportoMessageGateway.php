<?php

declare(strict_types=1);

namespace App\Modules\Messages\MessageGateway;

use App\Packages\ApiClients\Rapporto\RapportoApiClient;
use App\Packages\Enums\Rapporto\RapportoMessageTypeEnum;
use App\Packages\Support\PhoneNumber;

class RapportoMessageGateway implements MessageGateway
{
    public function __construct(
        private readonly RapportoApiClient $rapportoApiClient
    ) {
    }

    public function sendSms(PhoneNumber $phone, string $message): void
    {
        $this->rapportoApiClient->send($phone, $message, RapportoMessageTypeEnum::SMS);
    }
}
