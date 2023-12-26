<?php

declare(strict_types=1);

namespace App\Modules\Messages\MessageGateway;

use App\Packages\Support\PhoneNumber;

interface MessageGateway
{
    public function sendSms(PhoneNumber $phone, string $message): void;
}
