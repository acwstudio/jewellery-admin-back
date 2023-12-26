<?php

declare(strict_types=1);

namespace App\Packages\ApiClients\IntellinSms\Responses;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

class SendIntellinSmsResponseData extends Data
{
    public function __construct(
        #[MapInputName('error_num')]
        public readonly string $errorNum,
        #[MapInputName('message_id')]
        public readonly string $messageId,
        #[MapInputName('message_destination')]
        public readonly string $messageDestination,
        #[MapInputName('message_parts')]
        public readonly string $messageParts,
    ) {
    }
}
