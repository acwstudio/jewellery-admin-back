<?php

declare(strict_types=1);

namespace App\Modules\OTP\Support\Blueprints;

use App\Packages\Support\PhoneNumber;

class OtpCheckBlueprint
{
    public function __construct(
        public readonly string $code,
        public readonly PhoneNumber $phone,
    ) {
    }
}
